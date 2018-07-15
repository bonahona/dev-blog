<h1>Search result</h1>

<?php if(count($Posts) == 0):?>
    <h2 class="light-grey">Sorry, no results founds</h2>
<?php else:?>
    <h2>Found <?php echo count($Posts);?> result<?php if(count($Posts) != 1):?>s<?php endif;?>.</h2>
<?php foreach($Posts as $post):?>
    <div class="row">
        <div class="col-lg-12">
            <a href="<?php echo "/" . $post->NavigationTitle;?>">
                <h2>
                    <?php echo $post->Title;?><br/>
                    <span class="small light-grey">Posted on <?php echo date('Y-m-d', strtotime($post->PublishDate));?></span>
                </h2>
            </a>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo $post->HomePageText;?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php foreach($post->PostTags as $postTag):?>
                        <span class="label label-info"><?php echo $postTag->Tag->DisplayName;?></span>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach;?>
<?php endif;?>
