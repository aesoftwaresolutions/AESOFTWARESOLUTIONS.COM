import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Contact } from "@/lib/db/models";

export async function POST(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const body = await req.json();
  const { body: noteBody } = body;

  if (!noteBody || typeof noteBody !== "string" || noteBody.trim().length === 0) {
    return NextResponse.json({ error: "Note body must be a non-empty string" }, { status: 400 });
  }

  const updatedContact = await Contact.findByIdAndUpdate(
    params.id,
    {
      $push: {
        notesList: {
          body: noteBody.trim(),
          authorName: session.user.name || "Staff",
          createdAt: new Date(),
        },
      },
    },
    { new: true }
  );

  if (!updatedContact) {
    return NextResponse.json({ error: "Contact not found" }, { status: 404 });
  }

  const newNote = updatedContact.notesList[updatedContact.notesList.length - 1];
  return NextResponse.json({ note: newNote }, { status: 201 });
}
