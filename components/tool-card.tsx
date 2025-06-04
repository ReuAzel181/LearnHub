import Link from "next/link"
import { LucideIcon } from "lucide-react"

interface ToolCardProps {
  title: string
  description: string
  icon: LucideIcon
  href: string
}

export function ToolCard({ title, description, icon: Icon, href }: ToolCardProps) {
  return (
    <Link
      href={href}
      className="block p-6 bg-card hover:bg-accent/50 rounded-lg border border-border transition-colors"
    >
      <div className="flex items-center space-x-4">
        <div className="p-2 bg-primary/10 rounded-lg">
          <Icon className="h-6 w-6 text-primary" />
        </div>
        <div>
          <h3 className="text-lg font-semibold">{title}</h3>
          <p className="text-sm text-muted-foreground">{description}</p>
        </div>
      </div>
    </Link>
  )
} 