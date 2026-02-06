import Header from '@editorjs/header';
import List from "@editorjs/list";
import Checklist from '@editorjs/checklist';
import Delimiter from '@editorjs/delimiter';
import VkIframe from "./components/vkIframe.js";
import Youtube from "./components/youtube.js";
import Table from '@editorjs/table';
import CustomImage from './components/customImage.js';
import InlineCode from '@editorjs/inline-code';
import RawTool from '@editorjs/raw';
import Quote from '@editorjs/quote';
import Marker from '@editorjs/marker';
import LinkTool from '@editorjs/link';
import Accordion from 'editorjs-collapsible-block';
import EditorJS from "@editorjs/editorjs";
import EditorJsColumns from "@calumk/editorjs-columns/src/editorjs-columns.js";

const imageUploader = {
    uploadByFile(file) {
        const data = new FormData()
        data.append('image', file)
        return axios.post('/admin/editor-js/upload/image/file', data, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then((data) => {
            return {
                success: 1, file: {
                    url: data.data.url,
                }
            };
        });
    }, uploadByUrl(url) {
        return axios.post('/admin/editor-js/upload/image/url', {url}, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then((data) => {
            return {
                success: 1, file: {
                    url: data.data.url,
                }
            };
        });
    },
};

let column_tools = {
    header: Header, delimiter: Delimiter, image: {
        class: CustomImage, config: {
            uploader: imageUploader, endpoints: {
                byFile: '/admin/editor-js/upload/image/file', byUrl: '/admin/editor-js/upload/image/file'
            }
        }, shortcut: editorJsConf.image.shortcut
    }, linkTool: {
        class: LinkTool, config: {
            endpoint: '/moonshine/editor-js-field/fetch/url',
        }, inlineToolbar: false, shortcut: editorJsConf.link.shortcut
    }, marker: {
        class: Marker, shortcut: editorJsConf.marker.shortcut
    }, vkVideo: {
        class: VkIframe, shortcut: editorJsConf.vkVideo.shortcut
    }, youtube: {
        class: Youtube, shortcut: editorJsConf.youtube.shortcut
    }
}

export default class Config {

    static get getTools() {
        const tools = {}

        if (editorJsConf.columns.activated) {
            tools.columns = {
                class: EditorJsColumns, config: {
                    EditorJsLibrary: EditorJS, tools: column_tools,
                }, toolbox: [{
                    icon: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 12V4h2v8H8z"/>
  </svg>`,
                },],
            }
        }

        if (editorJsConf.accordion.activated) {
            tools.accordion = {
                class: Accordion, config: {
                    defaultExpanded: true, maxBlockCount: 10, disableAnimation: false, overrides: {
                        styles: {
                            blockWrapper: 'background-color: lightyellow;',
                            blockContent: 'border-left: 2px solid #ccc;',
                            lastBlockContent: 'border-bottom: 2px solid #ccc;',
                            insideContent: 'padding: 10px;',
                        },
                    },
                },
            };
        }

        if (editorJsConf.header.activated) {
            tools.header = {
                class: Header, shortcut: editorJsConf.header.shortcut
            };
        }
        if (editorJsConf.list.activated) {
            tools.list = {
                class: List, inlineToolbar: editorJsConf.list.inlineToolbar, config: {
                    defaultStyle: 'unordered'
                }, shortcut: editorJsConf.list.shortcut
            }
        }
        if (editorJsConf.image.activated) {
            tools.image = {
                class: CustomImage,
                config: {
                    uploader: imageUploader,
                },
                shortcut: editorJsConf.image.shortcut
            }
        }
        if (editorJsConf.quote.activated) {
            tools.quote = {
                class: Quote, shortcut: editorJsConf.quote.shortcut
            };
        }
        if (editorJsConf.vkVideo.activated) {
            tools.vkVideo = {
                class: VkIframe, shortcut: editorJsConf.vkVideo.shortcut
            };
        }
        if (editorJsConf.youtube.activated) {
            tools.youtube = {
                class: Youtube, shortcut: editorJsConf.youtube.shortcut
            };
        }
        if (editorJsConf.delimiter.activated) {
            tools.delimiter = Delimiter;
        }
        if (editorJsConf.table.activated) {
            tools.table = {
                class: Table, inlineToolbar: editorJsConf.table.inlineToolbar
            }
        }
        if (editorJsConf.raw.activated) {
            tools.raw = RawTool;
        }
        if (editorJsConf.marker.activated) {
            tools.marker = {
                class: Marker, shortcut: editorJsConf.marker.shortcut
            };
        }
        if (editorJsConf.checklist.activated) {
            tools.checklist = {
                class: Checklist,
                inlineToolbar: editorJsConf.checklist.inlineToolbar,
                shortcut: editorJsConf.checklist.shortcut
            };
        }
        if (editorJsConf.link.activated) {
            tools.linkTool = {
                class: LinkTool, config: {
                    endpoint: '/moonshine/editor-js-field/fetch/url',
                }, inlineToolbar: false, shortcut: editorJsConf.link.shortcut
            };
        }
        if (editorJsConf.inlineCode.activated) {
            tools.inlineCode = {
                class: InlineCode, inlineToolbar: false, shortcut: editorJsConf.inlineCode.shortcut
            };
        }


        return tools;
    }

}
