 <script
              src="https://code.jquery.com/jquery-3.5.1.js"
              integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
              crossorigin="anonymous"></script>
 <script src='https://cdn.jsdelivr.net/jquery.cloudinary/1.0.18/jquery.cloudinary.js' type='text/javascript'></script>
 <script src="//widget.cloudinary.com/global/all.js" type="text/javascript"></script>
 
 <button id="submit">Upload Image</button>
<script>
$(function() {
    // Configure Cloudinary
    // with the credentials on
    // your Cloudinary dashboard
    $.cloudinary.config({ cloud_name: 'happio-dk', api_key: '793256347957874'});
    // Upload button
    var uploadButton = $('#submit');
    const presets = ["video"];
    const getMyUploadPresets = (cb) => cb(presets);
    // Upload-button event
    uploadButton.on('click', function(e){


        // Initiate upload
        /*cloudinary.openUploadWidget({
            cloud_name: 'happio-dk', 
            upload_preset: 'uovv3yyp', 
            sources: [
                "local"
            ],
            resource_type: 'image',
            showAdvancedOptions: false,
            cropping: false,
            multiple: false,
            defaultSource: "local",
            // stylesheet: {
            //     palette: {
            //         window: "#000000",
            //         sourceBg: "#000000",
            //         windowBorder: "#8E9FBF",
            //         tabIcon: "#FFFFFF",
            //         inactiveTabIcon: "#8E9FBF",
            //         menuIcons: "#2AD9FF",
            //         link: "#08C0FF",
            //         action: "#336BFF",
            //         inProgress: "#00BFFF",
            //         complete: "#33ff00",
            //         error: "#EA2727",
            //         textDark: "#000000",
            //         textLight: "#FFFFFF"
            //     },
            //     fonts: {
            //         default: null,
            //         "'Space Mono', monospace": {
            //             url: "https://fonts.googleapis.com/css?family=Space+Mono",
            //             active: true
            //         }
            //     }
            // } 
        },*/ 

        cloudinary.openUploadWidget({

            cloud_name: 'happio-dk',
            //uploadPreset: "preset1",
            //theme: 'purple', 
            upload_preset: 'wsbuncyf', 
            getUploadPresets: getMyUploadPresets,
            //eager_async: true,
            resource_type:'video',
            // eager : [
            //     {
            //         "quality" : "auto",
            //         "format" : "mp4"
            //     }
            // ],
            max_file_size: 524288000,
            show_powered_by:false,
            defaultSource: "local",
            client_allowed_formats : ["mp4","MP4", "MOV","mov","WMV","wmv","FLV","flv","webm","WEBM","AVI","avi","WebM","MKV","mkv"],
            button_caption : "Upload video",
            tags: ['cgal'], 
            public_id: "Happio/uploads/msb123",
            sources: [ 'local','camera'],
            stylesheet : "https://phpstack-525891-1724339.cloudwaysapps.com/themes/front/styles/widget_style.css",
            text: {
                
                "powered_by_cloudinary": "Happio",
                "sources.local.title": "Mine filer",
                "sources.local.drop_file": "Drop din fil her",
                "sources.local.drop_files": "Drop dine filer her",
                "sources.local.drop_or": "Eller",
                "sources.local.select_file": "Vælg fil",
                "sources.local.select_files": "Vælg filer",
                "sources.url.title": "Web adresse",
                "sources.url.note": "Billedfilens offentlige URL",
                "sources.url.upload": "Upload", 
                "sources.url.error": "Indtast venligst en gyldig HTTP URL",
                "sources.camera.title": "Kamera",
                "sources.camera.note": "Tjek venligst at din browser tillader optagelser med kamera, indtag den rette position og klik Optag" , 
                "sources.camera.capture": "Optag",
                "progress.uploading": "Uploader…",
                "progress.upload_cropped": "Upload",
                "progress.processing": "Behandler…",
                "progress.retry_upload": "Prøv igen",
                "progress.use_succeeded": "OK",
                "progress.failed_note": "Nogle af dine filer blev ikke uploadet.",

            },        
        }, 

        function(error, result) { 
            if(error) console.log(error);
            // If NO error, log image data to console
            console.log(result);
            var id = result[0].public_id;
            console.log(processImage(id));
            
            //window.location.reload();
        });
    });
})
function processImage(id) {
    var options = {
        client_hints: true,
    };
    return '<img src="'+ $.cloudinary.url(id, options) +'" style="width: 100%; height: auto"/>';
}

</script>