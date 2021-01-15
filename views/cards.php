<!-- Room count -->
<div class="card">
    <div class="card-header">
        Rooms
    </div>
    <div class="card-body">
        <p class="count">Total rooms</p>
        <h2><?= $nbr_rooms ?></h2>
        <p>Add room</p>
        <a href="/ddwt20_project/add_room/" class="btn btn-primary">List yours</a>
    </div>
    <!-- User count -->
    <div class="card-header">
        Users
    </div>
    <div class="card-body">
        <p class="count">Total registered users</p>
        <h2><?= $nbr_users ?></h2>
        <p>Join Now</p>
        <a href="/ddwt20_project/register/" class="btn btn-primary">Register</a>
    </div>
</div>
