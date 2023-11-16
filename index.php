<?php

declare(strict_types=1);

session_start();

require __DIR__ . "/header.php";
require __DIR__ . "/data-generated.php";

// SORT LOGIC:

if (isset($_POST["sortBy"], $_POST["sortOrder"])) {
  $_SESSION["sortBy"] = htmlspecialchars($_POST["sortBy"]); // ex. pages or author
  $_SESSION["sortOrder"] = htmlspecialchars($_POST["sortOrder"]); // asc or desc
}

// sort books based on form input:
if (is_numeric($books[0][$_SESSION["sortBy"]])) {
  // year, pages: sort numerically
  if ($_SESSION["sortOrder"] === "asc") {
    uasort($books, function ($a, $b) {
      return $a[$_SESSION["sortBy"]] - $b[$_SESSION["sortBy"]];
    });
  } elseif ($_SESSION["sortOrder"] === "desc") {
    uasort($books, function ($a, $b) {
      return $b[$_SESSION["sortBy"]] - $a[$_SESSION["sortBy"]];
    });
  }
} elseif (!is_numeric($books[0][$_SESSION["sortBy"]])) {
  // title, author, color: sort alphabetically
  if ($_SESSION["sortOrder"] === "asc") {
    uasort($books, function ($a, $b) {
      return strnatcmp($a[$_SESSION["sortBy"]], $b[$_SESSION["sortBy"]]);
    });
  } elseif ($_SESSION["sortOrder"] === "desc") {
    uasort($books, function ($a, $b) {
      return strnatcmp($b[$_SESSION["sortBy"]], $a[$_SESSION["sortBy"]]);
    });
  }
}

// make $sortBy and $sortOrder available in the HTML sort form:
$sortBy = $_SESSION["sortBy"];
$sortOrder = $_SESSION["sortOrder"];

// FILTER LOGIC:

if (isset($_POST["filterBy"])) {
  $_SESSION["filterBy"] = trim(htmlspecialchars(strtolower($_POST["filterBy"])));
}

// Initially $filteredBooks = $books, but if a search is made, $filteredBooks will be updated. 
$filteredBooks = $books;

// Filter books based on search(author and title):
if (isset($_SESSION["filterBy"])) {
  $filterBy = $_SESSION["filterBy"];
  $filteredBooks = array_filter($books, function ($var) use ($filterBy) {
    if (
      str_contains(strtolower($var["title"]), $filterBy) ||
      str_contains(strtolower($var["author"]), $filterBy)
    ) {
      return 1; // true (keep filtered book in $filteredBooks, otherwise remove it)
    }
  });
}

?>

<main>

  <h1>Bookshelf</h1>

  <section class="info-section">
    <!-- Display the selected book: -->
    <div class="selection">
      <?php if (isset($_GET["id"])) :
        $selectedBook = htmlspecialchars($_GET["id"]); ?>
        <p><strong>Chosen book:</strong></p>
        <p><strong>Title:</strong> <?= $books[$selectedBook]["title"]; ?></p>
        <p><strong>Author:</strong> <?= $books[$selectedBook]["author"]; ?></p>
        <p><strong>Genre:</strong> <?= $books[$selectedBook]["genre"]; ?></p>
        <p><strong>Release year:</strong> <?= $books[$selectedBook]["year"]; ?></p>
        <p><strong>Number of pages:</strong> <?= $books[$selectedBook]["pages"]; ?></p>
      <?php else : ?>
        <p><strong>Do one of the following:</strong></p>
        <ul>
          <li>Pick a book</li>
          <li>Sort the books</li>
          <li>Search for a book</li>
        </ul>
      <?php endif; ?>
    </div>

    <div class="sort-and-filter">

      <!-- select which parameter to sort by alphabetically + ASC or DESC. -->
      <div class="sort">
        <form action="index.php" method="post">
          <label for="sortBy"><strong>Sort by:</strong></label><br>
          <select id="sortBy" name="sortBy">
            <option value="title" <?php if ($sortBy == 'title') echo 'selected="true"'; ?>>Title</option>
            <option value="author" <?php if ($sortBy == 'author') echo 'selected="true"'; ?>>Author</option>
            <option value="pages" <?php if ($sortBy == 'pages') echo 'selected="true"'; ?>>Pages</option>
            <option value="year" <?php if ($sortBy == 'year') echo 'selected="true"'; ?>>Release year</option>
            <option value="color" <?php if ($sortBy == 'color') echo 'selected="true"'; ?>>Color of book</option>
            <!-- Color currently sorts by hex value... not ideal, might fix later. -->
          </select>
          <div class="buttons-sort-order">
            <button type="submit" value="asc" name="sortOrder" <?php if ($sortOrder == 'asc') echo 'class="active"'; ?>>ASC</button>
            <button type="submit" value="desc" name="sortOrder" <?php if ($sortOrder == 'desc') echo 'class="active"'; ?>>DESC</button>
          </div>
        </form>
      </div>

      <!-- Search and filter by title or author: -->
      <div class="filter">
        <form action="index.php" method="post">
          <label for="filterBy"><strong>Search title or author:</strong></label><br>
          <input type="text" name="filterBy" placeholder="<?= $_SESSION["filterBy"] ?>">
          <div class="button-filter"><button type="submit">OK</button></div>
        </form>
      </div>
    </div>
  </section>

  <!-- 
    TODO: 
    add a message when a search doesnt match anything
  -->

  <section class="bookshelf">
    <form action="index.php" method="get" class="form-bookshelf">
      <?php foreach ($books as $book) : ?>
        <?php foreach ($filteredBooks as $filteredBook) :
          if ($filteredBook == $book) : ?>

            <!-- Each book is a button and can be selected: -->
            <button type="submit" value="<?= $book["id"] ?>" name="id" class="book" style="
            background-color: <?= $book["color"] ?>;
            font-family: <?= $book["fonts"] ?> , sans-serif;
            width: <?= $book["pages"] / 4 ?>px;">
              <p><?= $book["title"] ?></p>
              <p><?= $book["author"] ?></p>
            </button>

          <?php endif; ?>
        <?php endforeach; ?>
      <?php endforeach; ?>

      <?php if (empty($filteredBooks)) : ?>
        <div class="book no-matches">No matches!</div>
      <?php endif; ?>

    </form>
  </section>
</main>

</body>

</html>