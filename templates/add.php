<main class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <form method="post" action="/image/add" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input id="title" name="title" class="form-control" placeholder="Enter the title here."/>
                    <?php if($violations): ?>
                        <?php foreach ($violations as $v): ?>
                        <div class="alert alert-warning">
                            <?= $v->getMessage() ?>
                        </div>
                        <?php endforeach ?>
                    <?php endif ?>
                    <?= $_csrf ?>
                    <input name="file" type="file" class="form-control"/>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="/" class="btn btn-primary">Cancel</a>
            </form>
        </div>
        <div class="col-md-6">
        </div>
    </div>
</main>