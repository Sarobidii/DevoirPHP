<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<style>
        .reaction-container {
            position: relative;
            display: inline-block;
        }

        .like-button {
            padding: 10px;
            cursor: pointer;
            display: inline-block;
        }

        .reaction-icons {
            display: none;
            position: absolute;
            top: -40px; 
            left: 0;
            background-color: white;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            flex-direction: row;
            z-index: 10;
        }

        .reaction {
            display: inline-block;
            margin: 0 5px;
            cursor: pointer;
            font-size: 25px;
            transition: transform 0.3s ease;
        }

        .reaction:hover {
            transform: scale(1.5); /* L'agrandissement au survol */
        }

        .like-button {
            font-size: 25px;
        }

        .reaction-container:hover .reaction-icons {
            display: flex; /* Afficher les icônes au survol */
        }
    </style>
    <h1>HEllo world</h1>
    <p>Bienvenue sur la page appuyer une reaction</p>
<div class="reaction-container">
  <div class="like-button">👍</div>
  <div class="reaction-icons">
    <?php
       /*  include 'db_connect.php';
        $sql2 = "SELECT id, type_reaction FROM reaction";
        $result2 = mysqli_query($conn, $sql2);
        if (mysqli_num_rows($result2) > 0) {
            mysqli_data_seek($result2, 0);
            while ($row2 = mysqli_fetch_assoc($result2)) {
                echo "<div class='reaction' data-reaction='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['type_reaction']) . "</div>";
            }
        } */
    ?>
  </div>
</div>

<script>
    const reactions = document.querySelectorAll('.reaction');
const likeButton = document.querySelector('.like-button');

reactions.forEach(reaction => {
  reaction.addEventListener('click', function() {
    const reactionId = this.getAttribute('data-reaction');
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = 'essai_trait.php';
    
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'reaction';
    input.value = reactionId;
    form.appendChild(input);
    
    document.body.appendChild(form);
    form.submit();
  });
});

</script>

</body>
</html>
 -->
 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer la couleur des icônes Font Awesome</title>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        .thumbs-up {
            color: blue; /* J'aime */
        }
        .heart {
            color: red; /* J'adore */
        }
        .haha {
            color: orange; /* Haha */
        }
        .sad {
            color: lightblue; /* Triste */
        }
        .angry {
            color: darkred; /* Grrr */
        }
        .solidarity {
            color: green; /* Solidarité */
        }
    </style>
</head>
<body>
    hello

    <!-- J'aime -->
    <i class="fa-solid fa-thumbs-up thumbs-up"></i>
    <i class="fa-regular fa-thumbs-up thumbs-up"></i>

    <!-- J'adore -->
    <i class="fa-solid fa-heart heart"></i>

    <!-- Haha -->
    <i class="fa-regular fa-face-grin-tears haha"></i>

    <!-- Triste -->
    <i class="fa-solid fa-face-sad-tear sad"></i>

    <!-- Grrr -->
    <i class="fa-solid fa-face-angry angry"></i>

    <!-- Solidarité -->
    <i class="fa-solid fa-handshake solidarity"></i>

</body>
</html>
