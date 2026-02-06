import Image from "@editorjs/image"

// extend the image tool to enhance the image removal lifecycle
export default class CustomImage extends Image {
    removed() {
        const {file} = this._data
        axios.post('/admin/editor-js/delete/image', {
            urlFile: file.url,
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then((response) => console.log(response))
            .catch((error) => console.log(error));
    }
}
