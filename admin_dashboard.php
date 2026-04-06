<?php
    session_start();

    if(!isset($_SESSION['user']) || $_SESSION['role'] != "admin"){
        header("Location:index.php");
        exit();
    }

    include "database.php";
    include "user.php";

    $db = new Database();
    $conn = $db->connect();
    $user = new User($conn);

    $stmt = $conn->prepare("SELECT * FROM users WHERE role != 'admin' ORDER BY id DESC");
    $stmt->execute();

    $users = $stmt->get_result();

    $stmt->close();

    $total = $conn->query("SELECT COUNT(*) as total FROM users WHERE role!='admin'")
                ->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">
            Admin Dashboard
        </span>
        <span class="text-white">
            Welcome <?php echo $_SESSION['user']; ?>
        </span>
        <a href="logout.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i>  Logout</a>
    </div>
</nav>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="mt-2">Total Users</h5>
                    <p class="fs-4 fw-bold">
                        <?php echo $users->num_rows; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="mt-2">Active Users</h5>
                    <p class="fs-4 fw-bold">
                        <?php echo $users->num_rows; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="mt-2">Admin</h5>
                    <p class="fs-4 fw-bold">1</p>
                </div>
            </div>
        </div>

    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                User List
            </h5>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="searchUser" class="form-control" placeholder="Search user...">
                </div>
            </div>
            <div class="table-responsive">
                <table id="userTable" class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $i = 1; ?>
                        <?php while($row = $users->fetch_assoc()){ ?>
                            <tr>
                                
                                <td><?php echo $i++; ?></td>
                                <td>
                                    <?php
                                        $image = "uploads/default.png";
                                        if(!empty($row['image']) && file_exists("uploads/".$row['image'])){$image = "uploads/".$row['image'];}
                                    ?>
                                    <img src="<?php echo $image; ?>" width="60" height="60" class="rounded-circle object-fit-cover">
                                </td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['mobile']; ?></td>
                                <td>
                                    <span class="text-<?php echo $row['role']=="admin" ? "danger":"primary"; ?>">
                                        <?php echo $row['role']; ?>
                                    </span>
                                </td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm deleteUser" data-id="<?php echo $row['id']; ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    <ul class="pagination" id="pagination"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/app.js"></script>

</body>
</html>