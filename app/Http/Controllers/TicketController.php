<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Notifications\TicketUpdateNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user    = auth()->user();
        $tickets = $user->isAdmin ? Ticket::latest()->get() : $user->tickets;
        // dd($tickets);
        return view('ticket.index', compact('tickets'));

        // ticket show all 
        // $user    = auth()->user();
        // $tickets = Ticket::all();
        // // $tickets = $user->isAdmin ? Ticket::latest()->get() : $user->tickets;
        // return view('ticket.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {

        // step 1 : first create the ticket 
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        // step 2 :  checking file is there or not
        if ($request->file('attachment')) {
            $ext = $request->file('attachment')->extension();
            $contents = file_get_contents($request->file('attachment'));
            $fileName = Str::random(25);
            $path = "attachments/$fileName.$ext"; // "attachment/$fileName.$ext"
            Storage::disk('public')->put($path, $contents);

            // step 3 : if there is file update the ticket with file path
            $ticket->update(['attachment' => $path]);
        }





        // return response()->redirect(route('ticket.index'));
        return redirect(route('ticket.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('ticket.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
        // dd('edit page');
        return view('ticket.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->except('attachment'));

        if ($request->has('status')) {
            // $user = User::find($ticket->user_id);
            $ticket->user->notify(new TicketUpdateNotification($ticket));
        }

        if ($request->file('attachment')) {
            Storage::disk('public')->delete($ticket->attachment);
            $this->storeAttachment($request, $ticket);
        }
        return redirect(route('ticket.index'));
        // $ticket->update($request->except('attachment'));

        // // if ($request->has('status')) {
        // //     // $user = User::find($ticket->user_id);
        // //     $ticket->user->notify(new TicketUpdatedNotification($ticket));
        // // }

        // if ($request->file('attachment')) {
        //     Storage::disk('public')->delete($ticket->attachment);
        //     $this->storeAttachment($request, $ticket);
        // }
        // return redirect(route('ticket.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //

        $ticket->delete();
        return redirect(route('ticket.index'));
    }

    // Store attachment file for refactoring
    protected function storeAttachment($request, $ticket)
    {
        $ext      = $request->file('attachment')->extension();
        $contents = file_get_contents($request->file('attachment'));
        $filename = Str::random(25);
        $path     = "attachments/$filename.$ext";
        Storage::disk('public')->put($path, $contents);
        $ticket->update(['attachment' => $path]);
    }
}
