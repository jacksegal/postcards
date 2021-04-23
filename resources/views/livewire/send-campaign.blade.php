<div>
    <form wire:submit.prevent="upload">
        <input type="file" wire:model="supportersUpload">

        @error('participants') <span class="error">{{ $message }}</span> @enderror

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <button type="submit">Upload Participants</button>
    </form>

    <select wire:model="campaignClass">
        @foreach(config('postcards.campaigns') as $campaign)
            <option value="{{ $campaign['class'] }}">{{ $campaign['name'] }}</option>
        @endforeach
    </select>

    <button wire:click="send">Send Campaign</button>
</div>
