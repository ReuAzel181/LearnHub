<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HeaderController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        Log::info('Search request received', [
            'query' => $query,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id() ?? 'guest',
            'timestamp' => now()->toDateTimeString()
        ]);
        
        try {
            // Example search results - replace with your actual search logic
            $results = [
                [
                    'title' => 'Introduction to Programming',
                    'description' => 'Learn the basics of programming with this comprehensive course',
                    'url' => '/courses/intro-to-programming',
                    'icon' => 'fa-code'
                ],
                [
                    'title' => 'Web Development Fundamentals',
                    'description' => 'Master HTML, CSS, and JavaScript',
                    'url' => '/courses/web-development',
                    'icon' => 'fa-globe'
                ],
                // Add more mock results as needed
            ];
            
            Log::info('Search completed successfully', [
                'query' => $query,
                'results_count' => count($results),
                'user_id' => auth()->id() ?? 'guest',
                'timestamp' => now()->toDateTimeString()
            ]);
            
            return response()->json(['results' => $results]);
        } catch (\Exception $e) {
            Log::error('Search failed', [
                'query' => $query,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id() ?? 'guest',
                'timestamp' => now()->toDateTimeString()
            ]);
            
            return response()->json(['error' => 'Search failed'], 500);
        }
    }

    public function notifications()
    {
        Log::info('Notifications request received', [
            'user_id' => auth()->id() ?? 'guest',
            'timestamp' => now()->toDateTimeString(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        try {
            // Example notifications - replace with your actual notifications logic
            $notifications = [
                [
                    'message' => 'New course available: Advanced JavaScript',
                    'time' => '5 minutes ago',
                    'icon' => 'fa-book',
                    'read' => false
                ],
                [
                    'message' => 'Your assignment has been graded',
                    'time' => '1 hour ago',
                    'icon' => 'fa-check-circle',
                    'read' => false
                ],
                [
                    'message' => 'Course reminder: Python Basics starts tomorrow',
                    'time' => '2 hours ago',
                    'icon' => 'fa-bell',
                    'read' => true
                ],
                // Add more mock notifications as needed
            ];
            
            Log::info('Notifications retrieved successfully', [
                'count' => count($notifications),
                'unread_count' => collect($notifications)->where('read', false)->count(),
                'user_id' => auth()->id() ?? 'guest',
                'timestamp' => now()->toDateTimeString()
            ]);
            
            return response()->json(['notifications' => $notifications]);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve notifications', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id() ?? 'guest',
                'timestamp' => now()->toDateTimeString()
            ]);
            
            return response()->json(['error' => 'Failed to retrieve notifications'], 500);
        }
    }

    public function markNotificationsAsRead()
    {
        Log::info('Mark notifications as read request received', [
            'user_id' => auth()->id() ?? 'guest',
            'timestamp' => now()->toDateTimeString(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
        
        try {
            // Add logic to mark all notifications as read
            // For now, just logging the action
            Log::info('Notifications marked as read successfully', [
                'user_id' => auth()->id() ?? 'guest',
                'timestamp' => now()->toDateTimeString()
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to mark notifications as read', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id() ?? 'guest',
                'timestamp' => now()->toDateTimeString()
            ]);
            
            return response()->json(['error' => 'Failed to mark notifications as read'], 500);
        }
    }
} 