@component('mail::message')
# New Book Review Notification

A new review has been posted for your book **{{ $review->book_title }}**.

**Rating:** {{ str_repeat('⭐', $review->rating) }}

**Review:**  
{{ $review->review }}

@component('mail::button', ['url' => url('/reviews')])
View All Reviews
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent