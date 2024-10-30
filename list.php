<?php include('menu.php');
include('login-check.php'); ?>

<body>
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <?php
                    if (isset($_POST['submit'])) {
                        $name = $_POST['name'];
                        $subject = $_POST['sub'];
                        $marks = $_POST['marks'];

                        //check if student already exists
                        $sql2 = "SELECT stu_name, stu_sub FROM students WHERE stu_name=:name AND stu_sub = :subject";
                        $stm = $conn->prepare($sql2);
                        $stm->bindParam(':name', $name);
                        $stm->bindParam(':subject', $subject);
                        $stm->execute();
                        if ($stm->rowCount() > 0) {
                            $_SESSION['update-cat'] = '<div class="error">Student Already Exists... (You can Update)</div>';
                            header("location:" . SITEURL . 'list.php');
                            exit();
                        } else {
                            //insert product

                            $sql = "INSERT INTO students (stu_name, stu_sub, stu_marks) VALUES (:name, :subject, :marks) ";

                            $statement = $conn->prepare($sql);
                            //bind param after values whatever is written in query ':category', $category
                            $statement->bindParam(':name', $name);
                            $statement->bindParam(':subject', $subject);
                            $statement->bindParam(':marks', $marks);
                            $statement->execute();
                            $_SESSION['update-cat'] = '<div class="update-msg">Student Added Successfully</div>';
                            header("location:" . SITEURL . 'list.php');
                            exit(); // added to stop further execution after redirection
                        }
                    }
                    ?>

            <div>
                <form id="updateForm" method="post" action="">

                    <div class="input-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="">
                        <div class="error-message" id="nameError"></div>
                    </div>
                    <div class="input-group">
                        <label for="subject">Subject</label>
                        <input id="sub" name="sub" value="">
                        <div class="error-message" id="subError"></div>
                    </div>
                    <div class="input-group">
                        <label for="marks">Marks</label>
                        <input id="marks" name="marks" value="">
                        <div class="error-message" id="marksError"></div>
                    </div>
                    <input type="hidden" name="id" value="">
                    <button type="submit" name="submit">Add</button>
                </form>
            </div>
        </div>
    </div>


    <div class="liststu">
        <?php
        if (isset($_SESSION['update-cat'])) {
            echo $_SESSION['update-cat']; // Display the session message
            unset($_SESSION['update-cat']); // Unset the session variable after displaying it
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // sl_no
                $sl_no = 1;

                // SQL query
                $sql = "SELECT * FROM students";

                // Execute query
                $statement = $conn->prepare($sql);

                $statement->execute();

                // Fetch all rows
                $admins = $statement->fetchAll(PDO::FETCH_ASSOC);

                // Check if there are any rows
                if (count($admins) > 0) {
                    // Loop through each row
                    foreach ($admins as $admin) {
                        // Get individual data
                        $id = $admin['stu_id'];
                        $name = $admin['stu_name'];
                        $subject = $admin['stu_sub'];
                        $marks = $admin['stu_marks'];
                ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $subject; ?></td>
                            <td><?php echo $marks; ?></td>
                            <td>

                                <a href="edit-student.php?id=<?php echo $id; ?>"><button type="button" class="edit-btn">Edit</button></a>
                                <a href="delete.php?id=<?php echo $id; ?>"><button type="button" class="delete-btn">Delete</button></a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    // If no students found
                    echo "<tr><td>No Students found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button id="addButton" class="add-btn">Add</button>



    </div>
    <?php include('footer.php'); ?>

    <script>
        document.getElementById('addButton').addEventListener('click', function() {
            document.getElementById('modal').style.display = 'block';
        });

        document.querySelector('.close-button').addEventListener('click', function() {
            document.getElementById('modal').style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            const modal = document.getElementById('modal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Form validation
        document.getElementById('updateForm').addEventListener('submit', function(event) {
            let valid = true;

            // Clear previous error messages
            document.getElementById('nameError').innerText = '';
            document.getElementById('subError').innerText = '';
            document.getElementById('marksError').innerText = '';

            // Validate name
            const name = document.getElementById('name').value.trim();
            if (name === '') {
                document.getElementById('nameError').innerText = 'Name is required.';
                valid = false;
            } else if (!/^[A-Za-z\s,.]{3,}$/.test(name)) { 
                document.getElementById('nameError').innerText = 'Invalid Name (Must be at least 3 characters).';
                valid = false;
            }

            // Validate subject
            const subject = document.getElementById('sub').value.trim();
            if (subject === '') {
                document.getElementById('subError').innerText = 'Subject is required.';
                valid = false;
            } else if (!/^[A-Za-z\s,]{3,}$/.test(subject)) {
                document.getElementById('subError').innerText = 'Subject must contain at least 3 letters.';
                valid = false;
            }

            // Validate marks
            const marks = document.getElementById('marks').value.trim();
            if (marks === '') {
                document.getElementById('marksError').innerText = 'Marks are required.';
                valid = false;
            } else if (!/^\d{1,}$/.test(marks)) {
                document.getElementById('marksError').innerText = 'Invalid Marks (Marks must be at least 1 digit).';
                valid = false;
            }

            // Prevent form submission if any validation fails
            if (!valid) {
                event.preventDefault();
            }
        });
    </script>