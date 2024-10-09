<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">

                    <div class="fas fa-close close" @click.self="$emit('close')"></div>


                    <div v-if="errors" class="mb-4">
                        <div v-for="errorList in errors">
                            <div v-for="error in errorList" class="text-danger">{{ error }}</div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1">New Folder Name:</div>
                        <input type="text" class="form-control" v-model="folderName" @keydown.enter="createFoler" autofocus>
                        <button class="btn btn-sm btn-primary col-12 mt-3" @click="createFoler">Create Folder</button>
                    </div>


                </div>
            </div>
        </div>
    </transition>
</template>




<script>
import axios from 'axios';
export default{
    props: ['prefix', 'path'],
    data() {
        return {
            folderName: null,
            errors: null,
        }
    },

    methods:{
        createFoler(){
            this.$parent.loading = true;
            axios.post(this.prefix + '/create-folder', {folder_name: this.folderName, path: this.path})
                .then(() => {
                    this.$parent.loading = false;
                    this.$emit('created');
                    this.$emit('close');
                })
                .catch(error => {
                    this.$parent.loading = false;
                    this.errors = error.response.data.errors;
                });
        }
    }
}
</script>
