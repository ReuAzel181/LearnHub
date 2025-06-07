"use client"

import * as React from "react"
import Link from "next/link"
import { usePathname } from "next/navigation"
import { useTheme } from "next-themes"
import { Button } from "@/components/ui/button"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"
import { Moon, Sun, Home, BookOpen, Calculator, Edit3, PenTool, Menu } from "lucide-react"
import {
  Sheet,
  SheetContent,
  SheetTrigger,
} from "@/components/ui/sheet"

const tools = [
  {
    title: "Home",
    href: "/",
    icon: Home,
  },
  {
    title: "Notes",
    href: "/tools/notes",
    icon: Edit3,
  },
  {
    title: "Draw",
    href: "/tools/draw",
    icon: PenTool,
  },
  {
    title: "Calculator",
    href: "/tools/calculator",
    icon: Calculator,
  },
  {
    title: "Dictionary",
    href: "/tools/dictionary",
    icon: BookOpen,
  }
]

export function DashboardNav() {
  const { setTheme } = useTheme()
  const pathname = usePathname()

  return (
    <header className="flex items-center justify-between p-4 border-b">
      <Link href="/" className="flex items-center space-x-2">
        <span className="text-2xl font-bold">LearnHub</span>
      </Link>
      <div className="flex items-center space-x-4">
        {/* Desktop Navigation */}
        <nav className="hidden md:flex items-center space-x-4">
          {tools.map((tool) => {
            const isActive = pathname === tool.href
            const Icon = tool.icon
            return (
              <Link 
                key={tool.href} 
                href={tool.href}
                className={`flex items-center space-x-1 px-3 py-2 rounded-md transition-colors ${
                  isActive 
                    ? "bg-primary/10 text-primary" 
                    : "hover:bg-accent hover:text-accent-foreground"
                }`}
              >
                <Icon className="h-4 w-4" />
                <span>{tool.title}</span>
              </Link>
            )
          })}
        </nav>

        {/* Mobile Navigation */}
        <Sheet>
          <SheetTrigger asChild className="md:hidden">
            <Button variant="outline" size="icon">
              <Menu className="h-5 w-5" />
              <span className="sr-only">Toggle menu</span>
            </Button>
          </SheetTrigger>
          <SheetContent side="right">
            <nav className="flex flex-col gap-4 mt-8">
              {tools.map((tool) => {
                const isActive = pathname === tool.href
                const Icon = tool.icon
                return (
                  <Link 
                    key={tool.href} 
                    href={tool.href}
                    className={`flex items-center space-x-2 p-2 rounded-md transition-colors ${
                      isActive 
                        ? "bg-primary/10 text-primary" 
                        : "hover:bg-accent hover:text-accent-foreground"
                    }`}
                  >
                    <Icon className="h-5 w-5" />
                    <span>{tool.title}</span>
                  </Link>
                )
              })}
            </nav>
          </SheetContent>
        </Sheet>

        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="outline" size="icon">
              <Sun className="h-[1.2rem] w-[1.2rem] rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0" />
              <Moon className="absolute h-[1.2rem] w-[1.2rem] rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100" />
              <span className="sr-only">Toggle theme</span>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuItem onClick={() => setTheme("light")}>
              Light
            </DropdownMenuItem>
            <DropdownMenuItem onClick={() => setTheme("dark")}>
              Dark
            </DropdownMenuItem>
            <DropdownMenuItem onClick={() => setTheme("system")}>
              System
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </header>
  )
} 