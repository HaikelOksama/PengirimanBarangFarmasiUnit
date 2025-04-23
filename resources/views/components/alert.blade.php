@props(['type' => 'success', 'message' => ''])

<div class="toast toast-end toast-top">
    @switch($type)
        @case('info')
            <div class="alert alert-info">
                <span>{{ $message ?? 'info' }}</span>
            </div>
            @break
        
        @case('success')
            <div class="alert alert-success">
                <div class="flex">
                    <span>{{ $message ?? 'success' }} </span>
                    <flux:icon.check-circle  class="ms-3"/>
                </div>
            </div>
            @break
        @default
        <div class="alert alert-success">
            <span>Message sent successfully.</span>
        </div>
    @endswitch
    
</div>