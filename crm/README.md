# aeCRM — `crm.aesoftwaresolutions.com`

This directory contains the full aeCRM Next.js application, deployed as a standalone
Node.js service on the Hostinger VPS and served at `crm.aesoftwaresolutions.com`.

## Stack

- **Framework**: Next.js 14 (App Router)
- **Database**: MongoDB Atlas
- **Auth**: NextAuth.js
- **Hosting**: Hostinger VPS via PM2 + Nginx reverse proxy

---

## Hostinger VPS Setup

### 1. SSH into VPS and navigate to the web root

```bash
ssh root@<your-vps-ip>
cd /var/www
```

### 2. Clone the repo and enter the crm subdirectory

```bash
git clone https://github.com/aesoftwaresolutions/aesoftwares-theme.git
cd aesoftwares-theme/crm
```

### 3. Install dependencies

```bash
npm install
```

### 4. Create your environment file

```bash
cp .env.example .env
nano .env
```

Fill in all required values (see `.env.example` for keys):

```env
MONGODB_URI=mongodb+srv://...
NEXTAUTH_SECRET=your-secret-here
NEXTAUTH_URL=https://crm.aesoftwaresolutions.com
NEXT_PUBLIC_TENANT_PRESET=general-business
```

### 5. Build for production

```bash
npm run build
```

### 6. Start with PM2

```bash
npm install -g pm2
pm2 start npm --name "aecrm" -- start -- -p 3001
pm2 save
pm2 startup
```

### 7. Configure Nginx reverse proxy

Add a new server block in Nginx (e.g. `/etc/nginx/sites-available/crm`):

```nginx
server {
    listen 80;
    server_name crm.aesoftwaresolutions.com;

    location / {
        proxy_pass http://localhost:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
```

```bash
ln -s /etc/nginx/sites-available/crm /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
```

### 8. Add SSL with Certbot

```bash
certbot --nginx -d crm.aesoftwaresolutions.com
```

### 9. Point DNS

In Hostinger DNS panel, add an **A record**:

| Type | Name | Value         |
|------|------|---------------|
| A    | crm  | <your-vps-ip> |

---

## Switching Industry Preset

Set `NEXT_PUBLIC_TENANT_PRESET` in `.env` to any of:

| Value              | Entity label | Use case              |
|--------------------|--------------|-----------------------|
| `general-business` | Contact      | Default — any industry |
| `mortgage-lending` | Borrower     | Loan origination teams |
| `real-estate`      | Client       | Agents & brokerages    |
| `healthcare`       | Patient      | Clinics & wellness     |
| `saas`             | Account      | Software companies     |
| `retail-ecommerce` | Customer     | Stores & brands        |

After changing the preset, run `npm run build && pm2 restart aecrm`.

---

## Updating

```bash
cd /var/www/aesoftwares-theme
git pull origin master
cd crm
npm install
npm run build
pm2 restart aecrm
```
