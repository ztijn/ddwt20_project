<!-- Room count -->
<div class="card">
    <div class="card-header">
        Rooms
    </div>
    <div class="card-body">
        <p class="count">Total rooms available:</p>
        <h2><?= $nbr_rooms_available ?></h2>
        <p>Login to you account to see the available rooms</p>
        <a href="/ddwt20_project/login/" class="btn btn-primary">Login</a>
    </div>
</div>
<p></p>
<!-- User account -->
<div class="card">
    <div class="card-header">
        Your Account
    </div>
    <div class="card-body">
        <p class="count"><b>Name:</b> <?=$user_info['full_name']?></p>
        <p class="count"><b>User type:</b> <?=$user_info['role']?></p>
        <p class="count"><b>Preferred language:</b> <?=$user_info['language']?></p>
        <p class="count"><b>Email:</b> <?=$user_info['email']?></p>
        <p class="count"><b>Phone:</b> <?=$user_info['phone']?></p>
        <a href="/ddwt20_project/myaccount/?user_id=<?= $_SESSION['user_id']?>" class="btn btn-primary">Edit Account</a>
        <a href="/ddwt20_project/logout/" class="btn btn-primary">Logout</a>
    </div>
</div>

