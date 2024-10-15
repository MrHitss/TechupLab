<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;
use App\Models\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Validate Task data
     * Store the Files
     * Create Task, Notes, Attachments
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|in:New,Incomplete,Complete',
            'priority' => 'required|in:High,Medium,Low',
            'notes' => 'required|array',
            'notes.*.subject' => 'required|string',
            'notes.*.note' => 'required|string',
            'notes.*.attachments' => 'sometimes|array',
            'notes.*.attachments.*' => 'file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        try {

            return DB::transaction(function () use ($validatedData) {

                $task = Task::create([
                    'subject' => $validatedData['subject'],
                    'description' => $validatedData['description'],
                    'start_date' => $validatedData['start_date'],
                    'due_date' => $validatedData['due_date'],
                    'status' => $validatedData['status'],
                    'priority' => $validatedData['priority'],
                ]);

                $notesData = [];

                $notesData = [];

                foreach ($validatedData['notes'] as $noteData) {
                    $attachments = [];

                    if (!empty($noteData['attachments'])) {
                        foreach ($noteData['attachments'] as $attachment) {
                            $path = $attachment->store('attachments', 'public');
                            $attachments[] = Storage::url($path);
                        }
                    }

                    $notesData[] = [
                        'task_id' => $task->id,
                        'subject' => $noteData['subject'],
                        'note' => $noteData['note'],
                        'attachments' => json_encode($attachments),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                Notes::insert($notesData);

                return response()->json($task->load('notes'), 201);

            });

        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    /**
     * Fetch tasks with notes and attachments
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $filters = $request->filter ?? [];

        $tasks = Task::with('notes')
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['due_date']), fn($q) => $q->where('due_date', '<=', $filters['due_date']))
            ->when(isset($filters['priority']), fn($q) => $q->where('priority', $filters['priority']))
            ->when(isset($filters['notes']), fn($q) => $q->has('notes'))
            ->orderBy('priority', 'asc')
            ->withCount('notes')
            ->get();

        return response()->json($tasks, 200);
    }
}
