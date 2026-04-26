# AE Softwares Theme — Quick Start

## Install in WordPress

1. In WordPress admin → **Appearance → Themes → Add New → Upload Theme**
2. Upload `aesoftwares-theme.zip`
3. Click **Activate**

---

## Required: Download GSAP (free)

The cinematic scroll animations need two JS files that aren't included
(too large to bundle, and you should always get them fresh from the source).

1. Go to **https://gsap.com/docs/v3/Installation/**
2. Click **"Download GSAP"**
3. Unzip and upload these two files via FTP/SFTP to:
   ```
   /wp-content/themes/aesoftwares-theme/assets/js/vendor/gsap.min.js
   /wp-content/themes/aesoftwares-theme/assets/js/vendor/ScrollTrigger.min.js
   ```

> **No GSAP account needed. Both files are 100% free.**
> The theme falls back to a CDN copy automatically if local files are missing,
> so the site works either way — but local is better for production.

---

## Set up the Front Page

1. Create a new **Page** in WordPress (Pages → Add New)
2. Title it anything (e.g. "Home")
3. In the right sidebar under **Page Attributes → Template**, select **"Front Page"**
4. Publish the page
5. Go to **Settings → Reading**
6. Set **"Your homepage displays"** to **"A static page"**
7. Choose the page you just created as the **Homepage**

---

## Configure the AI Chatbot (n8n)

1. In WordPress admin → **AE Settings**
2. Paste your n8n webhook URL into the **n8n Webhook URL** field
3. Save

The `ae-assistant` chatbot on the front page and contact page will now
forward messages through WordPress to your n8n instance.

---

## LiteSpeed Cache Settings (Hostinger VPS)

If you use LiteSpeed Cache, add these to:
**LiteSpeed Cache → Page Optimization → Tuning → JS Deferred Excludes**

```
gsap.min.js
ScrollTrigger.min.js
cinematic.js
lenis.min.js
ae-assistant.js
ae-products.js
ae-testimonials.js
ae-cta.js
ae-greetings.js
main.js
```

Also **disable**:
- JS Combine (breaks script load order)
- CSS Combine (optional, safer to disable while testing)

After saving → **LiteSpeed Cache → Manage → Purge All**

---

## Page Templates Available

| Template | Use for |
|---|---|
| Front Page | Your homepage with cinematic scroll |
| About Page | Company about page with testimonials |
| Services Page | Full services grid |
| Contact Page | Contact info + embedded AE Assistant |
| Default Template | Standard content pages |

---

## What the Cinematic Scroll Does

| Section | Desktop | Mobile |
|---|---|---|
| Hero | 3-layer parallax (dots, orbs, iPhone) | Fade-in on load |
| Stats | Count-up numbers on scroll | Same |
| "What we do" | Pinned + scrubbed headline reveal | Staggered fade |
| Services | Fade-up | Same |
| Process | Horizontal scroll track | Vertical stack |
| Testimonials | Fade-up | Same |
| CTA | Scale-in from centre | Same |

All animations are automatically disabled for users with
**prefers-reduced-motion** enabled.

---

## Troubleshooting

**Animations not running**
→ Open browser console — look for `[cinematic] GSAP or ScrollTrigger not found`
→ Check Network tab: gsap.min.js must load before ScrollTrigger.min.js and cinematic.js

**Pinned section flickers on scroll**
→ LiteSpeed is rewriting script tags — re-check the exclude list above

**Chatbot says "not configured"**
→ Set the n8n webhook URL in AE Settings
