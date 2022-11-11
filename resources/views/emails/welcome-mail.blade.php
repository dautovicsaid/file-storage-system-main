<x-mail::message>
# Dear {{$new_user->name}}, welcome to FileStorageSystem

You can use this app to manage your files online!

<x-mail::button :url="'http://localhost:8000'">
Go to FileStorageSystem
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
