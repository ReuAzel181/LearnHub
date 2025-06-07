"use client"

import * as React from 'react'
import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import { DashboardNav } from '@/components/dashboard-nav'
import { Edit2, Trash2 } from 'lucide-react'

interface Note {
  id: string;
  title: string;
  content: string;
  date: string;
}

const tools = [
  {
    title: "Notes",
    href: "/tools/notes",
    icon: "📝",
    bgColor: "bg-yellow-300",
    className: "action-button notes-button"
  },
  {
    title: "Draw",
    href: "/tools/draw",
    icon: "✏️",
    bgColor: "bg-green-400",
    className: "action-button draw-button"
  },
  {
    title: "Calculator",
    href: "/tools/calculator",
    icon: "🔢",
    bgColor: "bg-blue-400",
    className: "action-button calculator-button"
  },
  {
    title: "Dictionary",
    href: "/tools/dictionary",
    icon: "📚",
    bgColor: "bg-orange-400",
    className: "action-button dictionary-button"
  }
]

export default function Home() {
  const router = useRouter();
  const [notes, setNotes] = useState<Note[]>([]);

  useEffect(() => {
    const savedNotes = localStorage.getItem('notes');
    if (savedNotes) {
      setNotes(JSON.parse(savedNotes));
    }
  }, []);

  const handleToolClick = (href: string, title: string) => {
    console.log(`Clicking ${title} button`);
    console.log(`Attempting to navigate to: ${href}`);
    router.push(href);
  };

  const handleEdit = (note: Note) => {
    router.push(`/tools/notes?edit=${note.id}`);
  };

  const handleDelete = (id: string) => {
    const updatedNotes = notes.filter(note => note.id !== id);
    setNotes(updatedNotes);
    localStorage.setItem('notes', JSON.stringify(updatedNotes));
  };

  return (
    <main className="min-h-screen">
      <DashboardNav />
      <div className="container mx-auto p-4 md:p-8">
        <h1 className="text-4xl font-bold mb-2">Welcome to LearnHub</h1>
        <p className="text-muted-foreground mb-8">Your all-in-one study companion. Select a tool to get started.</p>
        
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
          {tools.map((tool) => (
            <button
              key={tool.title}
              onClick={() => handleToolClick(tool.href, tool.title)}
              className={`${tool.bgColor} rounded-xl p-8 flex items-center justify-center transition-all hover:scale-105 hover:shadow-lg ${tool.className}`}
              style={{ aspectRatio: '16/9' }}
            >
              <div className="text-center">
                <div className="text-4xl mb-2">{tool.icon}</div>
                <h2 className="text-lg font-semibold">{tool.title}</h2>
              </div>
            </button>
          ))}
        </div>

        <div className="mt-8">
          <h2 className="text-2xl font-bold mb-4">Saved Notes</h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            {notes.map(note => (
              <div key={note.id} className="bg-card border rounded-lg p-4 hover:shadow-md transition-shadow">
                <div className="flex items-center justify-between mb-2">
                  <h3 className="font-medium">{note.title}</h3>
                  <div className="flex gap-2">
                    <button
                      onClick={() => handleEdit(note)}
                      className="text-muted-foreground hover:text-primary"
                    >
                      <Edit2 className="h-4 w-4" />
                    </button>
                    <button
                      onClick={() => handleDelete(note.id)}
                      className="text-muted-foreground hover:text-destructive"
                    >
                      <Trash2 className="h-4 w-4" />
                    </button>
                  </div>
                </div>
                <p className="text-sm text-muted-foreground mb-2">{note.date}</p>
                <p className="text-sm line-clamp-3">{note.content}</p>
              </div>
            ))}
            {notes.length === 0 && (
              <div className="col-span-full text-center text-muted-foreground p-4 border rounded-lg">
                No saved notes yet. Create notes using the Notes tool.
              </div>
            )}
          </div>
        </div>
      </div>
    </main>
  )
} 