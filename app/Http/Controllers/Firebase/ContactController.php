<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class ContactController extends Controller
{
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tablename = 'contacts';
    }

    public function index()
    {
        $contacts = $this->database->getReference($this->tablename)->getValue();
        return view('firebase.contact.index', compact('contacts'));
    }
    public function create()
    {
        return view('firebase.contact.create');
    }
    public function store(Request $request)
    {
        $postData = [
            'fname' => $request->first_name,
            'lname' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
        ];
        $postRef = $this->database->getReference($this->tablename)->push($postData);
        if($postRef)
        {
            return redirect('contacts')->with('status','Contact Add Successfully');
        }
        else
        {
            return redirect('contacts')->with('status','Contact Not Add');
        }
    }
    public function edit($id)
    {
        $key = $id;
        $editdata = $this->database->getReference($this->tablename)->getChild($key)->getValue();
        if($editdata)
        {
            return view('firebase.contact.edit', compact('editdata','key'));
        }
        else
        {
            return redirect('contacts')->with('status','Contact ID Not Found');
        }
        return view('firebase.contact.edit');
    }
    function update(Request $request, $id)
    {
        $key = $id;
        $updateData = [
            'fname' => $request->first_name,
            'lname' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
        ];
        $response = $this->database->getReference($this->tablename.'/'.$key)->update($updateData);
        if($response)
        {
            return redirect('contacts')->with('status','Contact Updated Successfully');
        }
        else
        {
            return redirect('contacts')->with('status','Contact Not Updated.');
        }
    }
}
