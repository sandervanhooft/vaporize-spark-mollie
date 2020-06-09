Vue.component('spark-update-profile-photo', {
    props: ['user'],

    /**
     * The component's data.
     */
    data() {
        return {
            form: new SparkForm({})
        };
    },


    methods: {
        /**
         * Update the user's profile photo.
         */
        update(e) {
            e.preventDefault();

            if ( ! this.$refs.photo.files.length) {
                return;
            }

            var self = this;

            this.form.startProcessing();

            // Stream the file to S3
            window.Vapor.store(this.$refs.photo.files[0], {
                progress: progress => {
                    this.uploadProgress = Math.round(progress * 100);
                }
            }).then(response => {
                // Now we send details of the uploaded photo to the server.
                // We will update the user after this action.
                axios.post('/settings/photo',{
                    bucket:         response.bucket,
                    key:            response.key,
                    content_type:   this.$refs.photo.files[0].type,
                })
                    .then(
                        () => {
                            Bus.$emit('updateUser');

                            self.form.finishProcessing();
                        },
                        (error) => {
                            self.form.setErrors(error.response.data.errors);
                        }
                    );
            });
        },
    },

    computed: {
        /**
         * Calculate the style attribute for the photo preview.
         */
        previewStyle() {
            return `background-image: url(${this.user.photo_url})`;
        }
    }

});
