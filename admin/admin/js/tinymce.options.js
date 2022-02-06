tinymce.init({
  selector: '.tinymce-large',
  height: 500,
  menubar:false,
  plugins: [
    'advlist autolink lists link'
  ],
  toolbar: [
    'undo redo | formatselect |  link | bold italic underline | removeformat',
    'alignleft aligncenter alignright | bullist numlist outdent indent'],
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});

tinymce.init({
  selector: '.tinymce-small',
  height: 250,
  forced_root_block : false,
  menubar:false,
  plugins: [
    'link'
  ],
  toolbar: [
    'bold italic underline | link | removeformat',
	],
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});