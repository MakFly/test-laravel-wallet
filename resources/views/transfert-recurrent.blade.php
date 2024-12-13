<x-app-layout>

    <!-- formulaire transact UX -->
    <form method="POST" action="{{ route('recurring-transfers.create') }}" class="space-y-4">
        @csrf

        <div class="">
            <input type="number" name="" id="" />
        </div>

        <div class="">
            <input type="number" name="" id="" />
        </div>

        <div class="">
            
        </div>

        <div class="">
        </div>

        <div class="">
        </div>

        <div class="">
        </div>
    </form>
</x-app-layout>