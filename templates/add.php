<style>
.box__uploading, .box__success, #uploadable, .box__error {
    display: none;
}

.box {
    min-height: 10em;
    padding: 1em;
    margin-bottom: 1em;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #eeeeee;
    outline: 2px dashed black;
    outline-offset: -10px;
}

.box.is-dragover {
    background-color: grey!important;
}

.box.is-uploading .box__input {
    visibility: none;
}

.box.box.is-uploading .box__uploading {
    display: block;
}
</style>
<main class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <form method="post" id="uploadform" action="/image/add" enctype="multipart/form-data">
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
                   
                </div>
                <div class="box">
                    <div class="box__input">
                        <label for="uploadable" class="box__filelabel">
                            <strong>Choose a file</strong>
                            <span class="box__dragndrop"> or drag it here</span>.
                        </label>
                        <input name="file" id="uploadable" type="file" class="form-control"/>
                    </div>
                    <div class="box__uploading">Uploading</div>
                    <div class="box__success">Done!</div>
                    <div class="box__error">Error!</div>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="/" class="btn btn-primary">Cancel</a>
            </form>
        </div>
        <div class="col-md-6">
        </div>
    </div>
</main>
