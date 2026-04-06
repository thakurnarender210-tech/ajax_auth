<?php
    session_start();
    
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    include "database.php";

    $db = new Database();
    $conn = $db->connect();

    //.....login check
    if(isset($_SESSION['id']) && isset($_SESSION['role'])){
        if(!isset($_SESSION['id'])){
            header("Location: login.php");
            exit();
        }
        if($_SESSION['role'] != "user"){
            header("Location: admin_dashboard.php");
            exit();
        }
    }

    //......get user data
    $id = $_SESSION['id'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if(!$row){
        header("Location: logout.php");
        exit();
    }

    //profile image
    $image = "default.png";
    if(!empty($row['image']) && file_exists("uploads/".$row['image'])){
        $image = $row['image'];
    }

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-1">
                        <img src="uploads/<?php echo $image; ?>" width="120" height="120"
                            class="rounded-circle border shadow object-fit-cover">
                    </div>
                    <p class="text-secondary mb-1">
                        <?php echo htmlspecialchars($row['email']); ?>
                    </p>
                    <p class="text-dark mb-2">
                        <?php echo htmlspecialchars($row['mobile']); ?>
                    </p>

                    <h3 class="fw-bold">
                        Welcome, <?php echo htmlspecialchars($row['name']); ?>
                    </h3>

                    <p class="text-success mb-4">
                        You have successfully logged into your dashboard.
                    </p>

                    <hr class="mb-4">
                    <div class="d-grid gap-3">
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal"
                            data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil-square"></i> Edit Profile
                        </button>

                        <button class="btn btn-outline-danger btn-lg" data-bs-toggle="modal" id="confirmDelete">
                            <i class="bi bi-trash"></i> Delete Account
                        </button>

                        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#sendMailModal">
                            <i class="bi bi-envelope"></i> Send Email
                        </button>
                        <input type="hidden" id="userid" value="<?php echo $row['id']; ?>">

                        <a href="logout.php" class="btn btn-dark btn-lg">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProfileModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="editProfileForm" enctype="multipart/form-data">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"
                        class="form-control mb-3" required>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"
                        class="form-control mb-3" required>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($row['password']); ?>"
                            class="form-control" required>
                        <span class="input-group-text" style="cursor:pointer;">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                    <input type="tel" name="mobile" maxlength="10"
                        value="<?php echo htmlspecialchars($row['mobile']); ?>" class="form-control mb-3" required
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">

                    <input type="file" name="image" class="form-control mb-3">
                    <div class="mb-1">
                        <img src="uploads/<?php echo $image; ?>" width="120" height="120"
                            class="rounded-circle border shadow object-fit-cover">
                    </div>

                    <button class="btn btn-success w-100">
                        Update Profile
                    </button>
                </form>
                <div id="editMsg"></div>
            </div>
        </div>
    </div>
</div>

    <!-- //......send mail  -->
    <div class="modal fade" id="sendMailModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Email</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            <div class="modal-body">
                <form id="sendMailForm" enctype="multipart/form-data">
                    <input type="email" name="to" class="form-control mb-3" placeholder="Receiver Email" required>
                    <input type="text" name="subject" class="form-control mb-3" placeholder="Subject" required>
                    <textarea name="message" class="form-control mb-3" placeholder="Message"></textarea>
                    <input type="file" name="file" class="form-control mb-3">
                    <button class="btn btn-success w-100">Send Mail</button>
                </form>
                <div id="mailMsg"></div>

                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/app.js"></script>

</body>
</html>