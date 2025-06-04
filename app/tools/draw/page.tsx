"use client"

import * as React from 'react'
import { useRef, useState, useEffect } from 'react'
import { DashboardNav } from '@/components/dashboard-nav'
import { Button } from '@/components/ui/button'
import { Download, Eraser, Circle, Square, Pencil, Type, Undo, Redo } from 'lucide-react'

export default function DrawPage() {
  const canvasRef = useRef<HTMLCanvasElement>(null)
  const [tool, setTool] = useState<'pencil' | 'eraser' | 'circle' | 'square' | 'text'>('pencil')
  const [color, setColor] = useState('#000000')
  const [size, setSize] = useState(2)
  const [isDrawing, setIsDrawing] = useState(false)
  const [lastX, setLastX] = useState(0)
  const [lastY, setLastY] = useState(0)

  const colors = ['#000000', '#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF']
  const sizes = [2, 4, 6, 8, 10]

  useEffect(() => {
    const canvas = canvasRef.current
    if (!canvas) return

    const ctx = canvas.getContext('2d')
    if (!ctx) return

    // Set canvas size
    canvas.width = canvas.offsetWidth
    canvas.height = canvas.offsetHeight

    // Set initial styles
    ctx.strokeStyle = color
    ctx.lineWidth = size
    ctx.lineCap = 'round'
  }, [color, size])

  const startDrawing = (e: React.MouseEvent<HTMLCanvasElement>) => {
    const canvas = canvasRef.current
    if (!canvas) return

    const rect = canvas.getBoundingClientRect()
    const x = e.clientX - rect.left
    const y = e.clientY - rect.top

    setIsDrawing(true)
    setLastX(x)
    setLastY(y)
  }

  const draw = (e: React.MouseEvent<HTMLCanvasElement>) => {
    if (!isDrawing) return

    const canvas = canvasRef.current
    if (!canvas) return

    const ctx = canvas.getContext('2d')
    if (!ctx) return

    const rect = canvas.getBoundingClientRect()
    const x = e.clientX - rect.left
    const y = e.clientY - rect.top

    ctx.beginPath()
    ctx.strokeStyle = tool === 'eraser' ? '#FFFFFF' : color
    ctx.lineWidth = tool === 'eraser' ? size * 2 : size

    switch (tool) {
      case 'pencil':
      case 'eraser':
        ctx.moveTo(lastX, lastY)
        ctx.lineTo(x, y)
        ctx.stroke()
        break
      case 'circle':
        const radius = Math.sqrt(Math.pow(x - lastX, 2) + Math.pow(y - lastY, 2))
        ctx.arc(lastX, lastY, radius, 0, 2 * Math.PI)
        ctx.stroke()
        break
      case 'square':
        const width = x - lastX
        const height = y - lastY
        ctx.strokeRect(lastX, lastY, width, height)
        break
    }

    setLastX(x)
    setLastY(y)
  }

  const stopDrawing = () => {
    setIsDrawing(false)
  }

  const downloadCanvas = () => {
    const canvas = canvasRef.current
    if (!canvas) return

    const link = document.createElement('a')
    link.download = 'drawing.png'
    link.href = canvas.toDataURL()
    link.click()
  }

  return (
    <div className="min-h-screen">
      <DashboardNav />
      <main className="container mx-auto p-4 md:p-8">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h1 className="text-3xl font-bold">Drawing Board</h1>
            <p className="text-muted-foreground">Create diagrams and sketches</p>
          </div>
          <div className="flex items-center gap-4">
            <div className="flex gap-2">
              {colors.map((c) => (
                <button
                  key={c}
                  className={`w-6 h-6 rounded-full border ${color === c ? 'ring-2 ring-primary ring-offset-2' : ''}`}
                  style={{ backgroundColor: c }}
                  onClick={() => setColor(c)}
                />
              ))}
            </div>
            <select
              className="px-2 py-1 border rounded"
              value={size}
              onChange={(e) => setSize(Number(e.target.value))}
            >
              {sizes.map((s) => (
                <option key={s} value={s}>
                  {s}px
                </option>
              ))}
            </select>
            <div className="flex gap-2">
              {[
                { name: 'pencil', icon: Pencil },
                { name: 'eraser', icon: Eraser },
                { name: 'circle', icon: Circle },
                { name: 'square', icon: Square },
                { name: 'text', icon: Type }
              ].map((t) => (
                <Button
                  key={t.name}
                  variant={tool === t.name ? "default" : "outline"}
                  size="icon"
                  onClick={() => setTool(t.name as typeof tool)}
                >
                  <t.icon className="h-4 w-4" />
                </Button>
              ))}
            </div>
            <div className="flex gap-2">
              <Button variant="outline" size="icon">
                <Undo className="h-4 w-4" />
              </Button>
              <Button variant="outline" size="icon">
                <Redo className="h-4 w-4" />
              </Button>
              <Button variant="outline" size="icon" onClick={downloadCanvas}>
                <Download className="h-4 w-4" />
              </Button>
            </div>
          </div>
        </div>
        
        <div className="bg-card border rounded-lg p-4">
          <canvas
            ref={canvasRef}
            className="w-full h-[60vh] border rounded cursor-crosshair bg-white"
            onMouseDown={startDrawing}
            onMouseMove={draw}
            onMouseUp={stopDrawing}
            onMouseLeave={stopDrawing}
          />
        </div>
      </main>
    </div>
  )
} 