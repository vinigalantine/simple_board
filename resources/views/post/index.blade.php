<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Posts</title>
    <style>
        textarea{
            margin-top: 10px;
            margin-bottom: 10px;
            resize: none;
        }

        button+button {
            margin-right: 5px;
        }

        body{
            background-color: #e5e5e5;
        }

        .stil-white{
            background-color: white;
        }

        .card+.card{
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8 stil-white" style="padding-top:10px; padding-bottom:10px;">
                <form id="new_post" action="" method="post">
                    @csrf
                    <input type="text" name="title" id="title" class="form-control clear-form" placeholder="Título">
                    <textarea name="body" id="body"rows="5" class="form-control clear-form" placeholder="Ex.: Hello world!"></textarea>
                    <button type="submit" class="btn btn-success float-right"><i class="fas fa-paper-plane"></i> Enviar</button>
                    <button type="reset" class="btn btn-danger float-right"><i class="fas fa-times"></i> Limpar</button>
                </form>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8 stil-white" id="div_posts" style="padding-bottom: 10px;"><h3>Posts anteriores</div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8 stil-white" id="div_posts" style="padding-bottom: 10px;">
            @foreach  ($posts as $post)
                <div class="card">
                    <form class="edit-post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $post->id }}">
                        <div class="card-header">
                            <h4>
                                <button id="del-{{ $post->id }}" type="button" onclick="delPost({{ $post->id }}, $(this))" class="btn btn-link text-danger float-right">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button id="edit-{{ $post->id }}" type="button" onclick="showEdit({{ $post->id }}, $(this))" class="btn btn-link text-info float-right">
                                    <i class="fa fa-pen"></i>
                                </button>
                                <button id="save-{{ $post->id }}" type="submit" onclick="" class="btn btn-link text-success float-right" style="display: none;">
                                    <i class="fa fa-save"></i>
                                </button>
                                <label id="lbl-{{ $post->id }}">{{ $post->title }}</label>
                                <input type="text" name="title" id="title-{{ $post->id }}" class="form-control" value="{{ $post->title }}" style="display: none;">
                            </h4>
                        </div>
                        <div class="card-body">
                            <p id="p-{{ $post->id }}">{{ $post->body }}</p>
                                <textarea name="body" id="body-{{ $post->id }}" class="form-control" rows="5" style="display: none;">{{ $post->body }}</textarea>
                        </div>
                    </form>
                </div>
            @endforeach
            </div>
            <div class="col-2"></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function(){
            $('#new_post').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url:'posts', 
                    type:'POST', 
                    data: $(this).serialize()
                }).done(function(data){
                    console.log(data.msg, data.id);

                    let html = postDiv({id: data.id, title: $('#title').val(), body: $('#body').val() })

                    $('#div_posts').append(html);

                    $('.clear-form').val(null).change();
                    setEditSubmitFunc();

                }).fail(function( data, textStatus, errorThrown){ console.log( textStatus )});
            });

            setEditSubmitFunc();
        });

        function showEdit(id, elmt){
            // let elmt = $(this);
            let form = elmt.parent().parent();
            console.log(elmt);
            elmt.hide();
            elmt.next().show();

            $('#lbl-'+id).hide();
            $('#title-'+id).show();
            $('#p-'+id).hide();
            $('#body-'+id).show();
        }

        function delPost(id, elmt){
            $.ajax({
                    url:'posts/'+id, 
                    type:'DELETE', 
                    data: {_token: $('[name=_token]').val()}
                }).done(function(data){
                    console.log(data.msg);
                    elmt.parent().parent().parent().parent().remove();

                }).fail(function( data, textStatus, errorThrown){ console.log( textStatus )});
        }

        function postDiv(data){
            let html = '<div class="card">'
                    +'<form class="edit-post">'
                        +'<input type="hidden" name="_token" value="'+$('[name=_token]').val()+'">'
                        +'<input type="hidden" name="id" value="'+data.id+'">'
                        +'<div class="card-header">'
                            +'<h4>'
                                +'<button id="del-'+data.id+'" type="button" onclick="delPost('+data.id+', $(this))" class="btn btn-link text-danger float-right">'
                                    +'<i class="fa fa-trash"></i>'
                                +'</button>'
                               +'<button id="edit-'+data.id+'" type="button" onclick="showEdit('+data.id+', $(this))" class="btn btn-link text-info float-right">'
                                    +'<i class="fa fa-pen"></i>'
                               +'</button>'
                                +'<button id="save-'+data.id+'" type="submit" onclick="" class="btn btn-link text-success float-right" style="display: none;">'
                                    +'<i class="fa fa-save"></i>'
                                +'</button>'
                                +'<label id="lbl-'+data.id+'">'+data.title+'</label>'
                                +'<input type="text" name="title" id="title-'+data.id+'" class="form-control" value="'+data.title+'" style="display: none;">'
                            +'</h4>'
                        +'</div>'
                        +'<div class="card-body">'
                            +'<p id="p-'+data.id+'">'+data.body+'</p>'
                                +'<textarea name="body" id="body-'+data.id+'" class="form-control" rows="5" style="display: none;">'+data.body+'</textarea>'
                        +'</div>'
                    +'</form>'
                +'</div>';

            return html;
        }

        function setEditSubmitFunc(){
            $('.edit-post').submit(function(e){
                e.preventDefault();
                let id = $(this).find('[name=id]').val();
                $.ajax({
                    url:'posts/'+id, 
                    type:'PUT', 
                    data: $(this).serialize()
                }).done(function(data){
                    console.log(data.msg);
                    
                    $('#save-'+id).hide();
                    $('#edit-'+id).show();

                    $('#lbl-'+id).html($('#title-'+id).val());
                    $('#p-'+id).html($('#body-'+id).val());

                    $('#lbl-'+id).show();
                    $('#title-'+id).hide();
                    $('#p-'+id).show();
                    $('#body-'+id).hide();

                    $('.clear-form').val(null).change();

                }).fail(function( data, textStatus, errorThrown){ console.log( textStatus )});
            })
        }

    </script>
</body>
</html>