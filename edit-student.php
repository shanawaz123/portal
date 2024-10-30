<?php include('menu.php'); 
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute SELECT query
    $sql = "SELECT * FROM students WHERE stu_id=:id";
    $statement = $conn->prepare($sql);
    $statement->bindParam(':id', $id);
    $statement->execute();
    $plot = $statement->fetch(PDO::FETCH_ASSOC);

    if ($plot) {
        $name = $plot['stu_name'];
        $subject = $plot['stu_sub'];
        $marks = $plot['stu_marks'];
    } else {
        $_SESSION['update-cat'] = "<div class='error'>Student not found</div>";
        header("Location: " . SITEURL . 'list.php');
        exit;
    }
} else {
    header("Location: update-student.php");
    exit;
}

// Form submit
if (isset($_POST['submit'])) {
    // Get the values from the form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $subject = $_POST['sub'];
    $marks = $_POST['marks'];

    // Update category
    $sql2 = "UPDATE students SET
            stu_name = :name,
            stu_sub = :subject,
            stu_marks = :marks
            WHERE stu_id = :id";

    $statement2 = $conn->prepare($sql2);
    $statement2->bindParam(':id', $id);
    $statement2->bindParam(':name', $name);
    $statement2->bindParam(':subject', $subject);
    $statement2->bindParam(':marks', $marks);
    $statement2->execute();

    if ($statement2->rowCount() > 0) {
        $_SESSION['update-cat'] = "<div class='update-msg'>Updated Successfully</div>";
        header("Location: " . SITEURL . 'list.php');
        exit;
    } else {
        $_SESSION['update-cat'] = "<div class='error'>Not Updated, Try Again..</div>";
        header("Location: " . SITEURL . 'list.php');
        exit;
    }
}
?>
<div class="container">
    <form id="updateForm" method="post" action="">
        <div class="input-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>">
            <div class="error-message" id="nameError"></div>
        </div>
        <div class="input-group">
            <label for="subject">Subject</label>
            <input id="sub" name="sub" value="<?php echo $subject; ?>">
            <div class="error-message" id="subError"></div>
        </div>
        <div class="input-group">
            <label for="marks">Marks</label>
            <input id="marks" name="marks" value="<?php echo $marks; ?>">
            <div class="error-message" id="marksError"></div>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <button type="submit" name="submit">Update</button>
    </form>
</div>

<script>
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
            document.getElementById('nameError').innerText = 'Name must contain at least 3 letters.';
            valid = false;
        }

        // Validate subject
        const subject = document.getElementById('sub').value.trim();
        if (subject === '') {
            document.getElementById('subError').innerText = 'Subject is required.';
            valid = false;
        } else if (!/^[A-Za-z]{3,}$/.test(subject)) {
            document.getElementById('subError').innerText = 'Subject must contain at least 3 letters.';
            valid = false;
        }

        // Validate marks
        const marks = document.getElementById('marks').value.trim();
        if (marks === '') {
            document.getElementById('marksError').innerText = 'Marks are required.';
            valid = false;
        } else if (!/^\d{1,}$/.test(marks)) {
            document.getElementById('marksError').innerText = 'Marks must be at least 1 digit.';
            valid = false;
        }

        // Prevent form submission if any validation fails
        if (!valid) {
            event.preventDefault();
        }
    });
</script>

<?php include('footer.php'); ?>