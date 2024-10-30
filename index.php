<?php include('menu.php');?>

    <div class="container">
        <?php
        $showModal = false; // Variable to control modal display
        if (isset($_POST['submit'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT username, password FROM users WHERE username=:username AND password=:password";
            $statement = $conn->prepare($sql);
            $statement->bindParam(':username', $username);
            $statement->bindParam(':password', $password);
            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            if ($row) { // Successful login
                $_SESSION['username'] = $row['username'];
                header('location:list.php');
                exit();
            } else { // Failed login
                $showModal = true; // Set to true to show the modal
            }
        }
        ?>
        <form method="post" action="">
            
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="submit">Login</button>
            <p class="message"><a href="#">Forgot Password?</a></p>
        </form>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal" style="display: <?php echo $showModal ? 'block' : 'none'; ?>;">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <p class="error">Invalid Username or Password</p>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Close the modal when the user clicks on <span> (x)
        var span = document.getElementsByClassName("close-button")[0];
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close the modal if the user clicks anywhere outside of the modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
<?php include('footer.php');?>

