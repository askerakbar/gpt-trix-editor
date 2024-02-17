document.addEventListener('alpine:init', () => {

    Alpine.data('trixEditor', ({ state }) => {
        return {
            state,
            init: function () {

                this.$refs.trix?.editor?.loadHTML(this.state)

                this.$watch('state', () => {
                    if (document.activeElement === this.$refs.trix) {
                        return
                    }

                    this.$refs.trix?.editor?.loadHTML(this.state)
                })
            },
            updateSelectedContent: function(content = ""){
                if(!content.trim().length){
                    return;
                }
                document.execCommand('insertHTML', true,  content);
                this.$refs.trix?.editor.recordUndoEntry(content);
            },
            updateContent: function(content = ''){
                if(!content.trim().length){
                    return;
                }
                var activeElement = window.document.activeElement;
                var editor = this.$refs.trix?.editor;
                editor.recordUndoEntry(content);
                editor.setSelectedRange([0,editor.getDocument().getLength()]);
                editor.insertHTML(content);
                activeElement && activeElement.focus && activeElement.focus();
            }
        }
    });

    // for some readson loading spinner is not working properly when used with window.getSelection()
    // have to find a better way
    Alpine.data('gptSpinnerComponent', () => ({
        loading: true,
        init() {
            this.loading = false
        },
        showLoader(){
           this.loading = true
        },
        hideLoader(){
           this.loading = false
        }
    }))

})

