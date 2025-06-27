<?php

namespace App\Domain\Contact\Http\Controllers\V1;

use App\Domain\Contact\Contracts\ContactServiceInterface;
use App\Domain\Contact\DTOs\ContactData;
use App\Domain\Contact\DTOs\ContactListData;
use App\Domain\Contact\Http\Requests\StoreContactRequest;
use App\Domain\Contact\Http\Resources\ContactResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        protected ContactServiceInterface $contactService
    ) {}

    public function store(StoreContactRequest $request)
    {
        $dto = ContactData::fromArray($request->validated());
        $contact = $this->contactService->upsert($dto);
        return new ContactResource($contact);
    }

    public function show($id)
    {   
        return new ContactResource($this->contactService->findById($id));
    }

    public function destroy($id)
    {
        $this->contactService->delete($id);
        return response()->json(['message' => 'Contact deleted']);
    }

    public function search(Request $request)
    {
        $results = $this->contactService->search($request->only(['name', 'phone', 'email_domain']));
        return ContactResource::collection($results);
    }

    public function call($id)
    {
        return new ContactResource($this->contactService->call($id));
    }

    public function index(Request $request)
    {
        $dto = ContactListData::fromRequest($request);

        $contacts = $this->contactService->list($dto);

        return ContactResource::collection($contacts);
    }

    
}
