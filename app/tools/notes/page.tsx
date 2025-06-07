"use client"

import * as React from 'react'
import { useState, useEffect } from 'react'
import { DashboardNav } from '@/components/dashboard-nav'
import { Button } from '@/components/ui/button'
import { Save, FolderPlus, Trash2, Edit2 } from 'lucide-react'
import { useSearchParams } from 'next/navigation'

interface Note {
  id: string;
  title: string;
  content: string;
  date: string;
}

export default function NotesPage() {
  const [content, setContent] = useState('')
  const [title, setTitle] = useState('')
  const [notes, setNotes] = useState<Note[]>([])
  const [editingNote, setEditingNote] = useState<Note | null>(null)
  const searchParams = useSearchParams()

  // Load notes from localStorage on initial render
  useEffect(() => {
    const savedNotes = localStorage.getItem('notes')
    if (savedNotes) {
      setNotes(JSON.parse(savedNotes))
    }
  }, [])

  // Check for edit parameter in URL
  useEffect(() => {
    const editId = searchParams.get('edit')
    if (editId && notes.length > 0) {
      const noteToEdit = notes.find(note => note.id === editId)
      if (noteToEdit) {
        handleEdit(noteToEdit)
      }
    }
  }, [notes, searchParams])

  // Save notes to localStorage whenever they change
  useEffect(() => {
    localStorage.setItem('notes', JSON.stringify(notes))
  }, [notes])

  const handleSave = () => {
    if (!title.trim() || !content.trim()) return

    if (editingNote) {
      setNotes(notes.map(note => 
        note.id === editingNote.id 
          ? { ...note, title, content, date: new Date().toLocaleString() }
          : note
      ))
      setEditingNote(null)
    } else {
      const newNote: Note = {
        id: Date.now().toString(),
        title,
        content,
        date: new Date().toLocaleString()
      }
      setNotes([newNote, ...notes])
    }

    setTitle('')
    setContent('')
  }

  const handleEdit = (note: Note) => {
    setEditingNote(note)
    setTitle(note.title)
    setContent(note.content)
  }

  const handleDelete = (id: string) => {
    setNotes(notes.filter(note => note.id !== id))
  }

  return (
    <div className="min-h-screen">
      <DashboardNav />
      <main className="container mx-auto p-4 md:p-8">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h1 className="text-3xl font-bold">Notes</h1>
            <p className="text-muted-foreground">Take and organize your study notes</p>
          </div>
          <div className="flex gap-4">
            <Button variant="outline" size="icon">
              <FolderPlus className="h-4 w-4" />
            </Button>
          </div>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div className="md:col-span-2">
            <div className="bg-card border rounded-lg p-4 mb-4">
              <input
                type="text"
                placeholder="Note title..."
                className="w-full mb-4 p-2 bg-background border rounded"
                value={title}
                onChange={(e) => setTitle(e.target.value)}
              />
              <textarea
                className="w-full h-[40vh] bg-background border rounded p-2 resize-none focus:outline-none"
                placeholder="Start typing your notes here..."
                value={content}
                onChange={(e) => setContent(e.target.value)}
              />
              <div className="flex justify-end mt-4">
                <Button onClick={handleSave}>
                  <Save className="h-4 w-4 mr-2" />
                  {editingNote ? 'Update' : 'Save'}
                </Button>
              </div>
            </div>
          </div>

          <div className="space-y-4">
            <h2 className="text-xl font-semibold mb-4">Saved Notes</h2>
            {notes.map(note => (
              <div key={note.id} className="bg-card border rounded-lg p-4">
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
              <div className="text-center text-muted-foreground p-4">
                No saved notes yet
              </div>
            )}
          </div>
        </div>
      </main>
    </div>
  )
} 