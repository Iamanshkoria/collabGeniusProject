document.getElementById('photoInput').addEventListener('change', function() {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(event) {
            var img = document.createElement('img');
            img.src = event.target.result;
            document.getElementById('preview').innerHTML = '';
            document.getElementById('preview').appendChild(img);
        }
        reader.readAsDataURL(file);
    }
});
