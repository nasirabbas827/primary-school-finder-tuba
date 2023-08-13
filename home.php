<?php
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php"); 
    exit();
}

// User is logged in, retrieve the user ID
$user_id = $_SESSION["id"];

// Function to fetch all cities from the database
function getCities($conn) {
    $query = "SELECT * FROM cities";
    $result = mysqli_query($conn, $query);

    $cities = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cities[] = $row;
        }
    } else {
        echo "Error fetching cities: " . mysqli_error($conn);
    }

    return $cities;
}

// Function to fetch all schools from the database
function getSchools($conn, $cityID = null) {
    $query = "SELECT * FROM schools";
    if ($cityID) {
        $query .= " WHERE city_id = $cityID";
    }
    $result = mysqli_query($conn, $query);

    $schools = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $schools[] = $row;
        }
    } else {
        echo "Error fetching schools: " . mysqli_error($conn);
    }

    return $schools;
}

// Function to fetch all faculty members from the database
function getFaculty($conn) {
    $query = "SELECT * FROM faculty";
    $result = mysqli_query($conn, $query);

    $faculty = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $faculty[] = $row;
        }
    } else {
        echo "Error fetching faculty members: " . mysqli_error($conn);
    }

    return $faculty;
}

// Function to fetch all courses from the database
function getCourses($conn) {
    $query = "SELECT c.*, s.name AS school_name, f.name AS faculty_name
              FROM course c
              INNER JOIN schools s ON c.school_id = s.school_id
              INNER JOIN faculty f ON c.faculty_id = f.faculty_id";
    $result = mysqli_query($conn, $query);

    $courses = array();
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row;
        }
    } else {
        echo "Error fetching courses: " . mysqli_error($conn);
    }

    return $courses;
}

// Fetch all cities
$cities = getCities($conn);

// Fetch all schools
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_form'])) {
    $cityID = $_POST['cityID'];
    $schools = getSchools($conn, $cityID);
} else {
    $schools = getSchools($conn);
}

// Fetch all faculty members
$faculty = getFaculty($conn);

// Fetch all courses
$courses = getCourses($conn);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schools, Faculty, and Courses</title>
    <link rel="stylesheet" href="./css/style.css">
    <style>
        #container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        select,
        button {
            padding: 5px;
            font-size: 16px;
        }

        h2 {
            margin-top: 20px;
            font-size: 24px;
        }

        .school,
        .faculty,
        .course {
            display: flex;
            margin: 20px 0;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        img {
            max-width: 400px;
            margin-right: 20px;
        }

        .details {
            flex: 1;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <?php include('navbar.php') ?>

    <div id="container">
        <?php if (isset($successMessage)) { ?>
            <div>
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>

        <?php if (isset($errorMessage)) { ?>
            <div>
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>
        <h2>Search School By City</h2>
        <form method="POST" action="">
            <div>
                <labe style="color: #ddd;" for="cityID">Select City:</label>
                <select id="cityID" name="cityID">
                    <option value="">-- Select City --</option>
                    <?php foreach ($cities as $city) { ?>
                        <option value="<?php echo $city['id']; ?>"><?php echo $city['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button class="button" type="submit" name="search_form">Search</button>
        </form>
        <h2>Our Schools</h2>
        <div>
            <?php foreach ($schools as $school) { ?>
                <div class="school">
                    <img src="./admin/school_images/<?php echo $school['picture']; ?>" alt="School Image">
                    <div class="details">
                        <h5><?php echo $school['name']; ?></h5>
                        <p>Contact Info: <?php echo $school['contact_info']; ?></p>
                        <p>Address: <?php echo $school['address']; ?></p>
                        <p>Fee: <?php echo $school['fee']; ?></p>
                        <p>Uniform: <?php echo $school['uniform']; ?></p>
                        <a href="admission_form.php?school_id=<?php echo $school['school_id']; ?>&city_id=<?php echo $school['city_id']; ?>">Apply Online</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <h2>Our Faculty Members</h2>
        <div>
            <?php foreach ($faculty as $member) { ?>
                <div class="faculty">
                    <img src="./admin/faculty_images/<?php echo $member['picture']; ?>" alt="Faculty Image">
                    <div class="details">
                        <h5><?php echo $member['name']; ?></h5>
                        <p>Qualification: <?php echo $member['qualification']; ?></p>
                        <p>Experience: <?php echo $member['experience']; ?></p>
                        <p>Contact Info: <?php echo $member['contact_info']; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>

        <h2>Our Courses</h2>
        <div>
            <?php foreach ($courses as $course) { ?>
                <div class="course">
                    <div class="details">
                        <h5><?php echo $course['subject_name']; ?></h5>
                        <p>School: <?php echo $course['school_name']; ?></p>
                        <p>Faculty: <?php echo $course['faculty_name']; ?></p>
                        <p>Book Name: <?php echo $course['book_name']; ?></p>
                        <p>Author Name: <?php echo $course['author_name']; ?></p>
                        <p>Publisher: <?php echo $course['publisher']; ?></p>
                        <p>Course Fee: <?php echo $course['course_fee']; ?></p>
                        <p>Course Duration: <?php echo $course['course_duration']; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

</body>

</html>
