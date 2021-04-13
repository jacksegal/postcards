<div>
    <form wire:submit.prevent="upload">
        <input type="file" wire:model="participants">

        @error('participants') <span class="error">{{ $message }}</span> @enderror

        <button type="submit">Upload Participants</button>
    </form>
</div>
