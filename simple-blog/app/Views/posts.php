<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Blog</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .post { border-bottom: 1px solid #ddd; padding: 20px 0; }
        .tags { font-size: 14px; color: gray; }
    </style>
</head>
<body>

    <h1>My Simple Blog</h1>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post->title); ?></h2>
                <p><?= nl2br(htmlspecialchars($post->content)); ?></p>
                <p class="tags"><strong>Tags:</strong> <?= htmlspecialchars($post->tags); ?></p>
                <p><em>By <?= htmlspecialchars($post->author); ?></em></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts available.</p>
    <?php endif; ?>

</body>
</html>
