<template>

    <div v-if="loading" class="loading_blur"></div>

    <div class="col-12 bg-dark text-white d-flex justify-content-between h3 m-0 p-2">
        <div></div>
        <div>Laravel Fast File Manager</div>
        <a href="https://github.com/keyvanlotfi/laravel-filemanager" target="_blank" class="fa-brands fa-github text-white"></a>
    </div>

    <div class="d-flex min-vh-100">

        <div class="sidebar d-none d-md-block col-md-2">
            <button class="btn btn-primary btn-sm col-12" @click="getItems('/')">Go to the root</button>
            <ul class="mt-3">
                <li v-for="directory in rootDirectories" :key="directory" class="cursor-pointer" @click="getItems(directory.name)">
                    <i :class="['fad h5 text-primary me-1', { 'fa-folder-open' : activeRootDirectory === directory.name, 'fa-folder-closed' : activeRootDirectory !== directory.name }]"></i> {{ directory.name }}
                </li>
            </ul>
        </div>


        <div class="content col-12 col-md-10 p-4" @click.self="deselectItems">
            <div v-if="moving.name && moving.moveFromPath === currentPath" class="text-center alert alert-primary font-bold">Navigate to new location to move {{ moving.name }}</div>
            <div v-if="moving.name && moving.moveFromPath !== currentPath" class="d-flex justify-content-center align-items-baseline">
                <button class="btn btn-success" @click="moveItem">Move here</button>
                <div class="fas fa-close ms-2 h5 cursor-pointer" @click="moving.name = false"></div>
            </div>

            <h2 class="mb-2">Content in: {{ currentPath }}</h2>


            <div class="d-flex mb-3">
                <div v-if="currentPath !== '/'" @click="goBack" class="cursor-pointer">
                    <i class="fas fa-arrow-left"></i>
                    Go Back
                </div>

                <div class="cursor-pointer ms-4" @click="getItems(currentPath)">
                    <i class="fas fa-refresh"></i>
                    Refresh
                </div>
            </div>

            <div class="grid">
                <!-- Directory -->
                <div v-for="(directory, index) in directories" :key="directory" :class="['item folder_item', { 'selected': directory.selected}]" @dblclick="navigateToDirectory(directory)" @click="selectItem(directory, index)"
                     @contextmenu.prevent="openContextMenu($event, directory, index, 'directory')" :title="directory.name">
                    <i class="fad fa-folder-closed icon"></i> {{ directory.name }}
                </div>

                <!-- File -->
                <div v-for="(file, index) in files" :key="file.name" :class="['item file_item', { 'selected': file.selected}]" @click="selectItem(file, index)" @contextmenu.prevent="openContextMenu($event, file, index, 'file')"
                     :title="file.name">
                    <img v-if="isImage(file)" :src="file.path" :alt="file.name" class="thumbnail"/>
                    <div v-else>
                        <i class="fa-duotone fa-file icon"></i>
                    </div>
                    <div>{{ limitString(file.name, 14) }}</div>
                </div>
            </div>


            <!-- Context Menu -->
            <div v-if="showContextMenu" :style="{ top: contextMenuY + 'px', left: contextMenuX + 'px' }" class="context-menu">
                <ul class="p-0">
                    <li class="p-2 cursor-pointer" @click="renameItem(selectedItem)"><i class="fad fa-file-signature text-primary me-2"></i>Rename</li>
                    <li class="p-2 cursor-pointer" @click="startMoveItem(selectedItem)"><i class="fad fa-files text-primary me-2"></i>Move</li>
                    <li class="p-2 cursor-pointer" @click="deleteItem(selectedItem)"><i class="fad fa-trash text-danger me-2"></i>Delete</li>
                    <li class="p-2 cursor-pointer" v-if="itemType === 'file'" @click="downloadFile(selectedItem)"><i class="fad fa-download text-info me-2"></i>Download</li>
                </ul>
            </div>


            <div class="add_button2 all_center" title="New Folder" @click="modals.newFolder = true">
                <span class="fas fa-folder-plus"></span>
            </div>

            <div class="add_button all_center" title="Upload image or file" @click="modals.dropzone = true">
                <span class="fad fa-upload"></span>
            </div>
        </div>


        <rename-modal v-if="modals.rename" @close="modals.rename = false" :selected_item="selectedItem" :prefix="prefix" @renamed="updateList"></rename-modal>
        <dropzone v-if="modals.dropzone" @close="modals.dropzone = false" :prefix="this.prefix" :path="currentPath" @uploaded="updateList"></dropzone>
        <new-folder v-if="modals.newFolder" :prefix="prefix" :path="currentPath" @created="updateList" @close="modals.newFolder = false"></new-folder>


    </div>
</template>


<script>
import axios from 'axios';
import RenameModal from './RenameModal.vue';
import NewFolder from './NewFolder.vue';
import Dropzone from './Dropzone.vue';


export default {
    props: ['prefix'],
    components: {
        RenameModal,
        NewFolder,
        Dropzone
    },
    data() {
        return {
            loading: false,
            files: [],
            directories: [],
            rootDirectories: [],
            previousPath: '',
            currentPath: '',
            activeRootDirectory: '', // which main directory is active


            // Context Menu
            showContextMenu: false,
            contextMenuX: 0,
            contextMenuY: 0,
            selectedItem: null,
            itemType: '',
            moving: {
                name: false,
                moveFromPath: '',
                item: {},
            },
            // Context Menu

            modals: {
                rename: false,
                dropzone: false,
                newFolder: false
            }
        };
    },
    mounted() {
        this.getItems('/');
        window.addEventListener('click', this.closeContextMenu);
    },
    beforeDestroy() {
        window.removeEventListener('click', this.closeContextMenu);
    },
    methods: {
        // Main Get
        getItems(path = '') {
            this.loading = true;
            axios.get(this.prefix + '/getitems', {params: {path}})
                .then(response => {
                    this.loading = false;
                    this.files = response.data.files;
                    this.directories = response.data.directories;
                    this.rootDirectories = response.data.rootDirectories;
                    this.currentPath = response.data.path || '/';


                    // to specify which root directory is active now
                    const arr = this.currentPath.split('/');
                    this.activeRootDirectory = arr[0];
                })
                .catch(error => {
                    console.error("There was an error fetching the files!", error);
                });
        },
        goBack() {
            if (this.currentPath === '/' || this.currentPath === '') {
                return;
            }

            // remove last location from current path
            const previousPath = this.currentPath.split('/').slice(0, -1).join('/');

            // go back to root if path is empty
            this.getItems(previousPath === '' ? '/' : previousPath);
        },
        navigateToDirectory(directory) {
            const newPath = this.currentPath !== '/' ? this.currentPath + '/' + directory.name : directory.name;
            this.getItems(newPath); // درخواست با مسیر کامل جدید
        },
        // Main Get


        // Selecting
        selectItem(item, index) {
            this.deselectItems();
            this.selectedItem = item;

            if (item.type === 'file') {
                this.files[index].selected = true;
            } else {
                this.directories[index].selected = true;
            }
        },
        deselectItems() {
            this.files.forEach(function (item) {
                item.selected = false;
            });
            this.directories.forEach(function (item) {
                item.selected = false;
            });
        },
        // Selecting


        // Context Menu
        openContextMenu(event, item, index, type) {
            this.selectItem(item, index);
            // Set position for context menu
            this.contextMenuX = event.clientX;
            this.contextMenuY = event.clientY;

            this.showContextMenu = true;

            this.selectedItem = item;
            this.itemType = type;
        },
        closeContextMenu() {
            this.showContextMenu = false;
        },
        renameItem() {
            this.modals.rename = true;
            this.closeContextMenu();
        },
        startMoveItem() {
            this.moving.name = this.selectedItem.name;
            this.moving.moveFromPath = this.currentPath;
            this.moving.item = this.selectedItem;
        },
        moveItem() {
            axios.post(this.prefix + '/move', {moving: this.moving, new_path: this.currentPath})
                .then(() => {
                    this.moving.name = false;
                    this.getItems(this.currentPath);
                })
                .catch(error => {
                    console.log(error.response.data);
                })
        },
        deleteItem() {
            let deleteItem = confirm('Are you sure to delete?');

            if (deleteItem) {
                axios.post(this.prefix + '/delete', this.selectedItem)
                    .then(() => {
                        this.getItems(this.currentPath);
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    });
            }
        },
        downloadFile() {
            axios({
                url: this.prefix + '/download',
                method: 'POST',
                responseType: 'blob', // receive the file as binary (blob)
                data: this.selectedItem // sending the selected file data
            })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;

                    // Use the file name from selectedItem instead of content-disposition
                    const fileName = this.selectedItem.name; // selectedItem contains the file name
                    link.setAttribute('download', fileName);

                    // Simulate click to trigger the download
                    document.body.appendChild(link);
                    link.click();

                    // Remove the temporary link after download
                    document.body.removeChild(link);
                })
                .catch(error => {
                    console.log(error.response.data);
                });
        },
        // Context Menu


        // Helper Methods
        isImage(file) {
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];
            return imageExtensions.includes(file.extension);
        },
        limitString(str, limit) {
            return str.length > limit ? str.slice(0, limit) + '...' : str;
        },
        updateList() {
            this.getItems(this.currentPath);
            this.loading = false;
        }
        // Helper Methods
    }
}
</script>


