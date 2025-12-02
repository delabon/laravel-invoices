<?php
/**
 * @var string $name
 * @var string $email
 */
?>
<div>
    <p>
        Hi {{ $name }},
    </p>
    <br>
    <p>
        Your account has been created successfully.
    </p>
    <br>
    <p>
        Best regards,
        <br>
        {{ config('app.name') }}
    </p>
</div>
