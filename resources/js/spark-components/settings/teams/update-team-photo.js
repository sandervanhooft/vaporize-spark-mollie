Vue.component('spark-update-team-photo', {
    props: ['user', 'team'],

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
                axios.post(this.urlForUpdate,{
                    bucket:         response.bucket,
                    key:            response.key,
                    content_type:   this.$refs.photo.files[0].type,
                })
                    .then(
                        () => {
                            Bus.$emit('updateTeam');
                            Bus.$emit('updateTeams');

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
         * Get the URL for updating the team photo.
         */
        urlForUpdate() {
            return `/settings/${Spark.teamsPrefix}/${this.team.id}/photo`;
        },


        /**
         * Calculate the style attribute for the photo preview.
         */
        previewStyle() {
            return `background-image: url(${this.team.photo_url})`;
        }
    }

});
