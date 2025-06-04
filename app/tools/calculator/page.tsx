"use client"

import * as React from 'react'
import { useState } from 'react'
import { DashboardNav } from '@/components/dashboard-nav'
import { Button } from '@/components/ui/button'
import { History, RefreshCcw, X } from 'lucide-react'

export default function CalculatorPage() {
  const [display, setDisplay] = useState('0')
  const [history, setHistory] = useState<string[]>([])
  const [showHistory, setShowHistory] = useState(false)
  const [memory, setMemory] = useState<number>(0)

  const buttons = [
    ['sin', 'cos', 'tan', '(', ')', 'C'],
    ['7', '8', '9', '÷', 'sqrt', 'π'],
    ['4', '5', '6', '×', 'x²', 'log'],
    ['1', '2', '3', '-', 'x^y', 'ln'],
    ['0', '.', '=', '+', '%', 'e']
  ]

  const handleClick = (value: string) => {
    switch (value) {
      case 'C':
        setDisplay('0')
        break
      case '=':
        try {
          let expression = display
            .replace('×', '*')
            .replace('÷', '/')
            .replace('π', Math.PI.toString())
            .replace('e', Math.E.toString())
          
          // Handle scientific functions
          expression = expression
            .replace(/sin\(/g, 'Math.sin(')
            .replace(/cos\(/g, 'Math.cos(')
            .replace(/tan\(/g, 'Math.tan(')
            .replace(/sqrt\(/g, 'Math.sqrt(')
            .replace(/log\(/g, 'Math.log10(')
            .replace(/ln\(/g, 'Math.log(')
            .replace(/x²/g, '**2')
            .replace(/x\^y/g, '**')

          const result = eval(expression)
          setHistory([`${display} = ${result}`, ...history])
          setDisplay(result.toString())
        } catch (error) {
          setDisplay('Error')
        }
        break
      case 'sin':
      case 'cos':
      case 'tan':
      case 'sqrt':
      case 'log':
      case 'ln':
        setDisplay(display === '0' ? `${value}(` : `${display}${value}(`)
        break
      case 'x²':
        try {
          const result = Math.pow(parseFloat(display), 2)
          setDisplay(result.toString())
          setHistory([`${display}² = ${result}`, ...history])
        } catch {
          setDisplay('Error')
        }
        break
      case 'π':
        setDisplay(display === '0' ? Math.PI.toString() : display + Math.PI.toString())
        break
      case 'e':
        setDisplay(display === '0' ? Math.E.toString() : display + Math.E.toString())
        break
      default:
        setDisplay(display === '0' ? value : display + value)
    }
  }

  return (
    <div className="min-h-screen">
      <DashboardNav />
      <main className="container mx-auto p-4 md:p-8">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h1 className="text-3xl font-bold">Scientific Calculator</h1>
            <p className="text-muted-foreground">Perform complex calculations</p>
          </div>
          <div className="flex gap-4">
            <Button 
              variant={showHistory ? "default" : "outline"} 
              size="icon"
              onClick={() => setShowHistory(!showHistory)}
            >
              <History className="h-4 w-4" />
            </Button>
            <Button variant="outline" size="icon" onClick={() => setDisplay('0')}>
              <RefreshCcw className="h-4 w-4" />
            </Button>
          </div>
        </div>
        
        <div className="flex gap-6">
          <div className="flex-1 max-w-md">
            <div className="bg-card border rounded-lg p-4">
              <div className="bg-muted p-4 rounded mb-4 text-right text-2xl font-mono overflow-x-auto">
                {display}
              </div>
              
              <div className="grid grid-cols-6 gap-2">
                {buttons.map((row, i) => (
                  row.map((btn, j) => (
                    <Button
                      key={`${i}-${j}`}
                      variant="outline"
                      className="h-12 text-sm md:text-base"
                      onClick={() => handleClick(btn)}
                    >
                      {btn}
                    </Button>
                  ))
                ))}
              </div>
            </div>
          </div>

          {showHistory && (
            <div className="w-72 bg-card border rounded-lg p-4">
              <div className="flex items-center justify-between mb-4">
                <h2 className="font-semibold">History</h2>
                <Button 
                  variant="ghost" 
                  size="icon"
                  onClick={() => setHistory([])}
                >
                  <X className="h-4 w-4" />
                </Button>
              </div>
              <div className="space-y-2">
                {history.map((item, index) => (
                  <div 
                    key={index} 
                    className="text-sm p-2 hover:bg-muted rounded cursor-pointer"
                    onClick={() => setDisplay(item.split(' = ')[1])}
                  >
                    {item}
                  </div>
                ))}
                {history.length === 0 && (
                  <div className="text-center text-muted-foreground text-sm p-4">
                    No calculations yet
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