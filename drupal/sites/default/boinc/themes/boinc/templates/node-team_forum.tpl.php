<?php
// $Id: node.tpl.php,v 1.10 2009/11/02 17:42:27 johnalbin Exp $

/**
 * @file
 * Theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $display_submitted: whether submission information should be displayed.
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 *   The following applies only to viewers who are registered users:
 *   - node-by-viewer: Node is authored by the user currently viewing the page.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $build_mode: Build mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $build_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * The following variables are deprecated and will be removed in Drupal 7:
 * - $picture: This variable has been renamed $user_picture in Drupal 7.
 * - $submitted: Themed submission information output from
 *   theme_node_submitted().
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see zen_preprocess()
 * @see zen_preprocess_node()
 * @see zen_process()
 */
?>
<?php $first_page = (!isset($_GET['page']) OR ($_GET['page'] < 1)); ?>

<?php if ($subscribe_link): ?>
  <div class="subscribe">
    <?php print $subscribe_link; ?>
  </div>
<?php endif; ?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix<?php echo ($first_page) ? '' : ' not-first-page'; ?>">
  
  <?php 
    if ($page) {
      // Set topic title as page title
      drupal_set_title($title);
      $subtitle = array();
      $team_forum_id = db_result(db_query("
        SELECT tfid FROM {boincteam_forum_node} WHERE nid = %d", $node->nid
      ));
      $team_forum = boincteam_forum_load($team_forum_id);
      // Grab a sample forum topic node to get the forum vocabulary name
      $sample = db_result(db_query("
        SELECT nid FROM {node} WHERE type = 'forum' LIMIT 1"
      ));
      $forum_node = node_load($sample);
      // Get vocabulary name and taxonomy name for subtitle breadcrumbs
      $taxonomy = taxonomy_get_term($forum_node->tid);
      if (module_exists('internationalization')) {
        $taxonomy = reset(i18ntaxonomy_localize_terms(array($taxonomy)));
      }
      if ($forum_vocab = taxonomy_vocabulary_load($taxonomy->vid)) {
        if (module_exists('internationalization')) {
          $forum_vocab->name = i18ntaxonomy_translate_vocabulary_name($forum_vocab);
        }
        $subtitle[] = l($forum_vocab->name, 'community/forum');
      }
      if ($team_forum) {
        $subtitle[] = l($team_forum->title, "community/teams/{$team_forum->nid}/forum/{$team_forum->tfid}");
      }
      $subtitle = implode(' &rsaquo; ', $subtitle);
    }
  ?>
  
  <h2 class="title"><?php print $subtitle; ?></h2>
  
  <?php if ($unpublished): ?>
    <div class="unpublished"><?php print bts('Unpublished'); ?></div>
  <?php endif; ?>
  
  <?php // Only show this post on the first page of a thread ?>
  <?php if ($first_page): ?>
    
    <div class="user">
      <?php
        $account = user_load(array('uid' => $uid));
        $user_image = boincuser_get_user_profile_image($uid);
        if ($user_image['image']['filepath']) {
          print '<div class="picture">';
          //print theme('imagecache', 'thumbnail', $user_image['image']['filepath'], $user_image['alt'], $user_image['alt']);
          print theme('imagefield_image', $user_image['image'], $user_image['alt'], $user_image['alt'], array(), false);
          print '</div>';
        }
        // Generate ignore user link
        $ignore_link = ignore_user_link('node', $node);
        //echo '<pre>' . print_r($node->links, TRUE) . '</pre>';
      ?>
      <div class="name"><?php print $name; ?></div>
      <?php if ($account->uid): ?>
        <div class="join-date">Joined: <?php print date('j M y', $account->created); ?></div>
        <div class="post-count">Posts: <?php print $account->post_count; ?></div>
        <div class="credit">Credit: <?php print $account->boincuser_total_credit; ?></div>
        <div class="rac">RAC: <?php print $account->boincuser_expavg_credit; ?></div>
        
        <div class="user-links">
          <div class="ignore-link"><?php print l($ignore_link['ignore_user']['title'],
            $ignore_link['ignore_user']['href'],
            array('query' => $ignore_link['ignore_user']['query'])); ?>
          </div>
          <div class="pm-link"><?php
            if ($user->uid AND ($user->uid != $account->uid)) {
              print l(bts('Send message'),
              privatemsg_get_link(array($account)),
              array('query' => drupal_get_destination()));
            } ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
    
    <div class="node-body">
      
      <?php /* if ($terms): ?>
        <div class="terms terms-inline"><?php print $terms; ?></div>
      <?php endif; */ ?>
      
      <?php if ($display_submitted): ?>
        <div class="submitted">
          <?php print date('j M Y H:i:s T', $node->created); ?>
        </div>
      <?php endif; ?>
      <div class="topic-id">
        Topic <?php print $node->nid; ?>
      </div>
      <div class="standard-links">
        <?php print $links; ?>
      </div>
      <?php if ($moderator_links): ?>
        <div class="moderator-links">
          <span class="label">(<?php print bts('moderation'); ?>:</span>
          <?php print $moderator_links; ?>
          <span class="label">)</span>
        </div>
      <?php endif; ?>
      
      <div class="content">
        <?php print $content; ?>
      </div>

      
    </div> <!-- /.node-body -->
    
  <?php endif; // first page ?>
  
</div> <!-- /.node -->
