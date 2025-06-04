"use client"

import * as React from 'react'
import { useRouter } from 'next/navigation'
import { DashboardNav } from '@/components/dashboard-nav'

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

  const handleToolClick = (href: string, title: string) => {
    console.log(`Clicking ${title} button`);
    console.log(`Attempting to navigate to: ${href}`);
    router.push(href);
  };

  return (
    <main className="min-h-screen">
      <DashboardNav />
      <div className="container mx-auto p-4 md:p-8">
        <h1 className="text-4xl font-bold mb-2">Welcome to LearnHub</h1>
        <p className="text-muted-foreground mb-8">Your all-in-one study companion. Select a tool to get started.</p>
        
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
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
      </div>
    </main>
  )
} 