<x-mail::message>
    # New File shared with you!


    <x-mail::button :url="'http://localhost:8000/shared-files'">Check out your shared files!
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
