<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AI Recipe Generator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div style="margin-top: 30px;" class="row text-center">
            <div class="col-md-12">
                <img width="100" height="100" src="{{url('logo.webp')}}" alt="">
                <h1>Recipe Generator</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form id="recipeForm" method="POST" action="">
                    <div class="form-group">
                      <label for="ingredients">Ingredients:</label>
                      <input name="ingredients" type="text" class="form-control" id="ingredients" placeholder="Potato, Tomato etc..">
                      <label for="cuisine">Cuisine</label>
                      <select class="form-control" name="Suggetion" id="cuisineDropdown">
                        <option value="pakistani">Sweet</option>
                        <option value="italian">delicious</option>
                        <option value="chinese">Hot</option>
                        <option value="mexican">chilly</option>
                        <option value="indian">spicy</option>
                        <option value="japanese">special</option>
                      </select>
                      
                    </div>
                    
                    <button type="submit" class="btn btn-danger">Generate</button>
                </form>
            </div>
        </div>
        <div style="margin-top: 10px" class="row">
            <div id="wait" class="col-md-12 d-none">
                <span class="text-muted ">Please Wait...</span>
            </div>
        </div>
        <div style="margin-top:10px;" class="row">
            <div id="recipe" class="col-md-12"></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script>
    <script>
        $( document ).ready(function() {
            $('#recipeForm').on('submit', function (e) {
                e.preventDefault()
                $('#recipe').html('')
                const ingredients = $('#ingredients').val();
                const cuisine = $('#cuisineDropdown').val();
                console.log("form submitted")  
                console.log(ingredients+'---'+cuisine)              
                $('#wait').removeClass('d-none')
                if (ingredients!= null && cuisine != null) {
                    $.ajax({
                            url: '{{ route("generate") }}',
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),  // CSRF token
                                ingredients: ingredients,
                                cuisine: cuisine
                            },
                            success: function (response) {
                                const converter = new showdown.Converter()
                                var markdownText = response.message
                                const html = converter.makeHtml(markdownText);
                                $('#recipe').html(html)
                                $('#wait').addClass('d-none')
                            },
                            error: function (xhr, status, error) {
                                // Handle errors
                                console.log(xhr.responseText);
                                $('#wait').addClass('d-none')
                                $('#recipeResult').html('<p>Error: ' + xhr.responseText + '</p>');
                            }
                        });
                }
            });

    
        })
    </script>
</body>
</html>