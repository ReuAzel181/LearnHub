"use client"

import * as React from 'react'
import { useState, useEffect } from 'react'
import { DashboardNav } from '@/components/dashboard-nav'
import { Button } from '@/components/ui/button'
import { Search, BookmarkPlus, Bookmark, Volume, X } from 'lucide-react'

interface WordDefinition {
  word: string;
  phonetic: string;
  meanings: {
    partOfSpeech: string;
    definitions: {
      definition: string;
      example?: string;
    }[];
  }[];
  phonetics: {
    audio?: string;
  }[];
}

export default function DictionaryPage() {
  const [searchTerm, setSearchTerm] = useState('')
  const [loading, setLoading] = useState(false)
  const [result, setResult] = useState<WordDefinition | null>(null)
  const [error, setError] = useState('')
  const [savedWords, setSavedWords] = useState<string[]>([])
  const [showSaved, setShowSaved] = useState(false)

  useEffect(() => {
    const saved = localStorage.getItem('savedWords')
    if (saved) {
      setSavedWords(JSON.parse(saved))
    }
  }, [])

  const handleSearch = async (word: string = searchTerm) => {
    if (!word.trim()) return
    setLoading(true)
    setError('')
    setResult(null)

    try {
      const response = await fetch(`https://api.dictionaryapi.dev/api/v2/entries/en/${word.toLowerCase()}`)
      if (!response.ok) throw new Error('Word not found')
      
      const data = await response.json()
      setResult({
        word: data[0].word,
        phonetic: data[0].phonetic || '',
        meanings: data[0].meanings,
        phonetics: data[0].phonetics
      })
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Failed to fetch definition')
    } finally {
      setLoading(false)
    }
  }

  const toggleSaveWord = (word: string) => {
    const newSavedWords = savedWords.includes(word)
      ? savedWords.filter(w => w !== word)
      : [...savedWords, word]
    
    setSavedWords(newSavedWords)
    localStorage.setItem('savedWords', JSON.stringify(newSavedWords))
  }

  const playAudio = (audioUrl: string) => {
    const audio = new Audio(audioUrl)
    audio.play()
  }

  return (
    <div className="min-h-screen">
      <DashboardNav />
      <main className="container mx-auto p-4 md:p-8">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h1 className="text-3xl font-bold">Dictionary</h1>
            <p className="text-muted-foreground">Look up definitions and examples</p>
          </div>
          <Button 
            variant={showSaved ? "default" : "outline"}
            size="icon"
            onClick={() => setShowSaved(!showSaved)}
          >
            <BookmarkPlus className="h-4 w-4" />
          </Button>
        </div>
        
        <div className="flex gap-6">
          <div className="flex-1 max-w-3xl">
            <div className="flex gap-4 mb-8">
              <input
                type="text"
                placeholder="Enter a word..."
                className="flex-1 px-4 py-2 rounded-lg border bg-background"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
              />
              <Button onClick={() => handleSearch()} disabled={loading}>
                <Search className="h-4 w-4 mr-2" />
                Search
              </Button>
            </div>
            
            <div className="bg-card border rounded-lg p-6">
              {loading && (
                <div className="text-center text-muted-foreground">
                  <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto mb-4" />
                  <p>Searching...</p>
                </div>
              )}

              {error && (
                <div className="text-center text-destructive">
                  <p>{error}</p>
                </div>
              )}

              {!loading && !error && !result && (
                <div className="text-center text-muted-foreground">
                  <Search className="h-12 w-12 mx-auto mb-4 opacity-20" />
                  <p>Enter a word above to see its definition, pronunciation, and examples.</p>
                </div>
              )}

              {result && (
                <div>
                  <div className="flex items-center justify-between mb-6">
                    <div>
                      <h2 className="text-2xl font-bold">{result.word}</h2>
                      {result.phonetic && (
                        <p className="text-muted-foreground">{result.phonetic}</p>
                      )}
                    </div>
                    <div className="flex gap-2">
                      {result.phonetics[0]?.audio && (
                        <Button
                          variant="outline"
                          size="icon"
                          onClick={() => playAudio(result.phonetics[0].audio!)}
                        >
                          <Volume className="h-4 w-4" />
                        </Button>
                      )}
                      <Button
                        variant={savedWords.includes(result.word) ? "default" : "outline"}
                        size="icon"
                        onClick={() => toggleSaveWord(result.word)}
                      >
                        {savedWords.includes(result.word) ? (
                          <Bookmark className="h-4 w-4" />
                        ) : (
                          <BookmarkPlus className="h-4 w-4" />
                        )}
                      </Button>
                    </div>
                  </div>

                  <div className="space-y-6">
                    {result.meanings.map((meaning, index) => (
                      <div key={index}>
                        <h3 className="font-semibold text-lg mb-2">
                          {meaning.partOfSpeech}
                        </h3>
                        <ol className="list-decimal list-inside space-y-2">
                          {meaning.definitions.map((def, i) => (
                            <li key={i} className="text-sm">
                              <span>{def.definition}</span>
                              {def.example && (
                                <p className="ml-6 mt-1 text-muted-foreground">
                                  Example: "{def.example}"
                                </p>
                              )}
                            </li>
                          ))}
                        </ol>
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>
          </div>

          {showSaved && (
            <div className="w-72 bg-card border rounded-lg p-4">
              <div className="flex items-center justify-between mb-4">
                <h2 className="font-semibold">Saved Words</h2>
                <Button 
                  variant="ghost" 
                  size="icon"
                  onClick={() => setSavedWords([])}
                >
                  <X className="h-4 w-4" />
                </Button>
              </div>
              <div className="space-y-2">
                {savedWords.map((word) => (
                  <div 
                    key={word}
                    className="flex items-center justify-between p-2 hover:bg-muted rounded cursor-pointer"
                    onClick={() => {
                      setSearchTerm(word)
                      handleSearch(word)
                    }}
                  >
                    <span className="text-sm">{word}</span>
                    <Button
                      variant="ghost"
                      size="icon"
                      onClick={(e) => {
                        e.stopPropagation()
                        toggleSaveWord(word)
                      }}
                    >
                      <X className="h-3 w-3" />
                    </Button>
                  </div>
                ))}
                {savedWords.length === 0 && (
                  <div className="text-center text-muted-foreground text-sm p-4">
                    No saved words yet
                  </div>
                )}
              </div>
            </div>
          )}
        </div>
      </main>
    </div>
  )
} 