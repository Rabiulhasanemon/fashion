<?php if ($questions) { ?>
<?php foreach ($questions as $question) { ?>
<div class="question-wrap">
  <p class="author"><span class="name"><?php echo $question['author']; ?></span> on <?php echo $question['date_added']; ?></p>
  <h3 class="question"><span class="hint">Q:</span> <?php echo $question['text']; ?></h3>
  <p class="answer"><span class="hint">A:</span> <?php echo $question['answer']; ?></p>
  <p class="author answerer"><span>By</span> <span>Ranger Fashion</span> <span><?php echo $question['date_added']; ?></span></p>
</div>
<?php } ?>
<div class="text-right"><?php echo $pagination; ?></div>
<?php } else { ?>
<div class="empty-content">
  <span class="icon material-icons">assignment</span>
  <div class="empty-text">There are no questions.</div>
</div>
<?php } ?>
