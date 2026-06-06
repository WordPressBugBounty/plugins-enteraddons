( function( $s) {
    "use strict";

    var obj, h = {};

    obj = {

        modal: false,
        onPreviewLoaded: function() {

            const $that = this;

            const target = $s('.MuiStack-root').eq(1);
            if (!$s('.MuiStack-root').find('.custom-icon').length ) {
                target.prepend('<div class="elementor-add-section-area-button enteraddons-ai-popup">AI</div>')
                
                $s(document).on("click", ".enteraddons-ai-popup", function () {
                    $that.aiModal()
                })
                
            }

            this.addTemplateButton(),
            window.elementor.$previewContents.on("click", ".add-enteraddons-template", _.bind(this.templateModal, this))
            window.elementor.$previewContents.on("click", ".enteraddons-ai-popup", _.bind(this.aiModal, this))

        },
        addTemplateButton: function() {

            var s = $s("#tmpl-elementor-add-section"),
                btn = '<div class="add-enteraddons-template"><i class="eicon-pojome"></i></div>';

            if( 0 < s.length ) {
                var t = s.text();
                    t =  t.replace( '<div class="elementor-add-section-drag-title', '<div class="elementor-add-section-area-button add-enteraddons-template"><i class="icon-enteraddons-temp-lib"></i></div><div class="elementor-add-section-area-button enteraddons-ai-popup">AI</div><div class="elementor-add-section-drag-title' );
                    s.text(t);
            }
        },
        templateModal: function() {

            let $oi = this;
            this.getModal().show();

            /***********************************************
             * Create app
            ************************************************/
            const app = Vue.createApp({

            data() {
                return {
                    source: enteraddonsGeteDitorData.api_source,
                    proVersionUrl:'https://enteraddons.com/',
                    version_type: enteraddonsGeteDitorData.version_type,
                    isPreview: false,
                    previewUrl: '',
                    templateId: '',
                    packageType: '',
                    tabs: [
                        {title:"Templates", slug:"templates"},
                        {title:"Blocks", slug:"blocks"},
                        {title:"Headers", slug:"headers"},
                        {title:"Footers", slug:"footers"}
                    ],
                    templates: [],
                    filters: [],
                    tab: 'templates',
                    search_text:'',
                    search_sort:'',
                    search_cat:'',
                    tabActive: 'templates',
                    library_cache: {}

                }
            },
            methods: {
                getElementsData(t) {

                    let $that = this;

                    if( $that.library_cache[t] ) {
                        let cacheData = $that.library_cache[t];
                            $that.templates = cacheData.templates;
                            $that.filters = cacheData.filters;
                        return;
                    }

                    $s.ajax({
                        url: enteraddonsGeteDitorData.ajaxurl,
                        type: "POST",
                        dataType: "json",
                        data: {
                            action: "editor_library_data",
                            nonce: enteraddonsGeteDitorData.enter_nonce,
                            id: t
                        },
                        beforeSend: function() {
                            h.showLoadingView()
                        },
                        success: function (res) {
                                                        
                            $that.templates = res.templates;
                            $that.filters = res.filters;
                            $that.library_cache[t] = {templates:res.templates,filters:res.filters}

                            h.hideLoadingView()
                        },
                        error: function(e) {
                            console.log( e );
                        }
                    })

                },
                changeSearchCatData (t) {
                    this.search_text = '';
                    this.search_sort = '';
                    this.search_cat = '';
                    this.tabActive = t;

                },
                sortSearchRoot(v) {
                    this.search_sort = v;
                },
                textSearchRoot(v) {
                    this.search_text = v;
                },
                updatePreviewCheck(template) {
                    this.templateId = template.id;
                    this.packageType = template.package;
                    this.previewUrl = template.url;
                    this.isPreview = true;
                },
                backToLibraryRoot() {
                    this.isPreview = false;
                },
                insertTemplateRoot( i ) {

                    var templateId = i;
                    
                    // Show pre loader                       
                    h.showLoadingView()

                    let n = {
                        unique_id: templateId, 
                        data: {
                            edit_mode: !0, 
                            display: !0, 
                            template_id: templateId, 
                            with_page_settings: true 
                        },
                        success: function( data ) {
                            
                            $e.run('document/elements/import', {
                                model: window.elementor.elementsModel,
                                data: data,
                                options: {}
                            });
                        },
                        error: function(err) {
                            console.log( err );
                        },
                        complete: function() {
                            //  Close modal after import template
                            obj.closeModal()
                            //  hide pre loader
                            h.hideLoadingView();
                        }
                    }
                    
                    elementorCommon.ajax.addRequest("get_template_data", n);
                    
                }
            },
            watch: {
                /*templates() {
                    this.$emit( 'templates', this.templates )
                },
                filters() {
                    this.$emit( 'filters', this.filters )
                }*/
            },
            mounted() {
                this.getElementsData(this.tab);
            }

            });

            /***********************************************
             * Component template library header
            ************************************************/
            app.component('template-library-header', {
                template: '#enteraddons-template-library-header',
                props: {
                    isPreview: Boolean,
                    set_active_tab: String
                },
                data() {
                    return {
                        tabs: this.$root.tabs
                    }
                },
                methods: {
                    getItemsByType(t) {
                        this.$root.getElementsData(t);
                        //
                        this.$emit( 'current_tab', t);
                    },
                    closeModal() {
                        $oi.getModal().hide()
                    }
                    
                }
            })

            
            /***********************************************
             * Component template library content
            ************************************************/
            app.component('template-library-content', {
                template: '#enteraddons-template-library-content',
                props: {
                    
                    set_filters: {
                        type: Array
                    },
                    set_version_type: {
                        type: String
                    },
                    set_sort_search: {
                        type: String
                    },
                    set_text_search: {
                        type: String
                    },
                    isPreview: {
                        type: Boolean
                    },
                    pro_url: {
                        type: String
                    }
                },
                emits: ['sort_search', 'text_search', 'preview_check', 'insert_template'],
                methods: {
                    getItemsByFilter(f) {
                        this.$root.search_cat = f
                    },
                    sortSearch(e) {
                        let value = e.target.value;
                        this.$emit( 'sort_search', value );
                    },
                    textSearch(e) {
                        let value = e.target.value;
                        this.$emit( 'text_search', value );
                    },
                    templatePreview( prevUrl ) {
                        this.$emit( 'preview_check', prevUrl );
                    },
                    insertTemplate( i ) {
                        this.$emit( 'insert_template', i );
                    },
                    sortFilter(templates) {
                        return templates.filter( template => {
                            return template.package.toLowerCase().includes(this.$root.search_sort.toLowerCase())
                        })
                    },
                    textFilter(templates) {
                        return this.$root.templates.filter( template => {
                            return template.title.toLowerCase().includes(this.$root.search_text.toLowerCase())
                        })
                    },
                    catFilter(templates) {
                        return templates.filter( template => {
                            return template.categories.toLowerCase().includes(this.$root.search_cat.toLowerCase())
                        })
                    }
                },
                computed: {
                    get_templates() {
                        return this.catFilter(this.sortFilter(this.textFilter( this.$root.templates )));
                    }
                }
              
            })

            /***********************************************
             * Component template library preview
            ************************************************/
            app.component('template-preview', {
                template: '#enteraddons-template-library-preview',
                data() {
                    return {
                        previewUrl: this.$root.previewUrl
                    }
                },
                methods: {}
                              
            })

            /***********************************************
             * Component template preview header
            ************************************************/
            app.component('template-preview-header', {
                template: '#enteraddons-template-library-preview-header',
                props: {
                    tempid: Number,
                    package_type: String,
                    pro_url: String
                },
                emits: ['back_to_library','insert_template'],
                data() {
                    return {
                        
                    }
                },
                methods: {
                    backToLibrary() {
                        this.$emit('back_to_library');
                    },
                    insertTemplate( i ) {
                        this.$emit( 'insert_template', i );
                    },
                    closeModal() {
                        obj.closeModal();
                    }
                }
                              
            })

            // Add components in lightbox header and message area
            $s('.dialog-lightbox-header').html('<template-preview-header v-if="isPreview" :tempid="templateId" :package_type="packageType" :pro_url="proVersionUrl" @back_to_library="backToLibraryRoot" @insert_template="insertTemplateRoot"></template-preview-header><template-library-header v-else @current_tab="changeSearchCatData" :set_active_tab="tabActive"></template-library-header>');
            $s('.dialog-lightbox-message').html('<template-preview v-if="isPreview"></template-preview><template-library-content v-else v-model="search_sort" v-model="search_text" @sort_search="sortSearchRoot" @text_search="textSearchRoot" :set_sort_search="search_sort" :set_text_search="search_text" @preview_check="updatePreviewCheck" @insert_template="insertTemplateRoot" :set_version_type="version_type" :set_filters="filters" :pro_url="proVersionUrl" ></template-library-content>');
            // App mounting
            app.mount('#enteraddonsModal');
        },
        aiModal: function () {

            let $oi = this;
            this.getModal().show();

            /***********************************************
             * Create app
            ************************************************/
            const app = Vue.createApp({

                data() {
                    return {
                        source: enteraddonsGeteDitorData.api_source,
                        proVersionUrl:'https://enteraddons.com/',
                        version_type: enteraddonsGeteDitorData.version_type,
                        prompt_text: ''
                    }
                },
                methods: {
                    promptTextRoot(v) {
                        this.prompt_text = v;
                    },
                },
                watch: {
                    /*templates() {
                        this.$emit( 'templates', this.templates )
                    },
                    filters() {
                        this.$emit( 'filters', this.filters )
                    }*/
                },
                mounted() {
                    
                }

            });

            /***********************************************
             * Component template library header
            ************************************************/
            app.component('template-ai-library-header', {
                template: '#enteraddons-template-ai-library-header',
                props: {
                    isPreview: Boolean,
                    set_active_tab: String
                },
                data() {
                    return {}
                },
                methods: {
                    closeModal() {
                        $oi.getModal().hide()
                        setTimeout(function () {
                            $s('.dialog-widget-content').removeClass('dialog-ai-widget-content-wrapper');
                        }, 500 )
                        
                    }
                }
            })

            
            /***********************************************
             * Component template library content
            ************************************************/
            app.component('template-ai-library-content', {
                template: '#enteraddons-template-ai-library-content',
                data() {
                    return {
                        form: {
                            prompt_text: '',
                            model_type: localStorage.getItem('ea_ai_model_type') || 'EA-AI EA1'
                        },
                        loading: false,
                        responseMessage: '',
                        chatHistory: JSON.parse(localStorage.getItem("ea_ai_builder_chat")) || [],
                        showCommands: false,
                        selectedIndex: 0,
                        searchQuery: '',
                        commands: enteraddonsGeteDitorData.slash_commands || []

                    }
                },
                methods: {

                    setModelType(value) {
                        this.form.model_type = value;
                        localStorage.setItem('ea_ai_model_type', value);
                    },

                    async aiRequest(i) {
                        
                        let that = this;

                        this.responseMessage = '';

                        try {

                            //
                            const chatContainer = $s('.enteraddons-ai-messages');
                            if (chatContainer.length) {
                                chatContainer.animate({
                                    scrollTop: chatContainer[0].scrollHeight
                                }, 400);
                            }

                            //
                            $s('.enteraddons-suggestions').hide();
                            $s('.enteraddons-ai-generate-btn').hide();
                            $s('.ea-loader-progress-bar').html('<div class="ea-ai-pre-loader"><div class="ea-ai-loader"></div><p>Thinking....</p></div>').show();

                            const promptText = this.form.prompt_text;

                            var $e = window.parent.$e;
                                let n = {
                                    unique_id: enteraddonsGeteDitorData.page_id, 
                                    data: {
                                        edit_mode: !0, 
                                        display: !0, 
                                        template_id: enteraddonsGeteDitorData.page_id,
                                        prompt_text: this.form.prompt_text,
                                        model_type: this.form.model_type,
                                        with_page_settings: true
                                    },
                                    success: function( data ) {

                                        $s('.ea-loader-progress-bar').html('<div class="ea-ai-pre-loader"><div class="ea-ai-loader"></div><p>Processing....</p></div>');

                                        setTimeout(function () {

                                            $s('.ea-loader-progress-bar').html('<div class="ea-ai-pre-loader"><div class="ea-ai-loader"></div><p>Finalizing....</p></div>');

                                            setTimeout(function () {

                                                $e.run('document/elements/import', {
                                                    model: window.elementor.elementsModel,
                                                    data: data,
                                                    options: {}
                                                });

                                                that.responseMessage = 'Build Successfully';
                                                that.chatHistory.push({ prompt: promptText, response: 'Build Successfully', time: Date.now() });
                                                localStorage.setItem("ea_ai_builder_chat", JSON.stringify(that.chatHistory));
                                                $s('.ea-loader-progress-bar').hide();
                                                $s('.enteraddons-suggestions').show();
                                                $s('.enteraddons-ai-generate-btn').show();

                                            }, 500);

                                        }, 600);

                                    },
                                    error: function(err) {
                                        that.responseMessage = err;
                                        that.chatHistory.push({ prompt: promptText, response: err, time: Date.now() });
                                        localStorage.setItem("ea_ai_builder_chat", JSON.stringify(that.chatHistory));

                                        $s('.ea-loader-progress-bar').hide();
                                        $s('.enteraddons-suggestions').show();
                                        $s('.enteraddons-ai-generate-btn').show();

                                    },
                                    complete: function () {
                                        
                                    }
                                }
                                                
                                elementorCommon.ajax.addRequest("get_ai_template_data", n);


                        } catch (error) {
                            this.responseMessage = error;
                        } finally {}

                    },

                    handleInput(e) {
                        const value = this.form.prompt_text;
                        const cursorPos = e.target.selectionStart;

                        // Get text before cursor
                        const textBeforeCursor = value.substring(0, cursorPos);

                        // Match `/something`
                        const match = textBeforeCursor.match(/\/(\w*)$/);

                        if (match) {
                            this.showCommands = true;
                            this.searchQuery = '/' + match[1];
                        } else {
                            this.showCommands = false;
                        }
                    },
                    handleKeydown(e) {

                        if (!this.showCommands) return;

                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            this.selectedIndex =
                                (this.selectedIndex + 1) % this.filteredCommands.length;
                        }

                        if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            this.selectedIndex =
                                (this.selectedIndex - 1 + this.filteredCommands.length) %
                                this.filteredCommands.length;
                        }

                        if (e.key === 'Enter') {
                            e.preventDefault();
                            this.selectCommand(this.filteredCommands[this.selectedIndex]);
                        }

                        if (e.key === 'Escape') {
                            this.showCommands = false;
                        }
                    },
                    selectCommand(cmd) {
                        const textarea = document.querySelector('textarea');
                        const cursorPos = textarea.selectionStart;

                        const value = this.form.prompt_text;

                        // Replace `/text` with selected command
                        const newText = value.replace(/\/(\w*)$/, cmd.label + ' ');

                        this.form.prompt_text = newText;

                        this.showCommands = false;
                        this.selectedIndex = 0;

                        this.$nextTick(() => {
                            textarea.focus();
                        });
                    },
                    selectSuggestion(cmd) {
                        const textarea = document.querySelector('textarea');

                        if ( cmd != '/' ) {
                            this.searchQuery = cmd;
                            this.form.prompt_text = this.filteredCommands[0].label;
                        }
                        //
                        if ( cmd == '/' ) {
                            this.showCommands = true;
                            this.searchQuery = cmd;
                            this.form.prompt_text = cmd;
                        }

                    }

                },
                watch: {
                    chatHistory() {
                        this.$nextTick(() => {
                            const el = this.$refs.chatMessages;
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                    }
                },
                mounted() {
                    this.$nextTick(() => {
                        const el = this.$refs.chatMessages;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                },
                computed: {
                    filteredCommands() {

                        if (!this.searchQuery) return this.commands;
                        // normalize input (remove slash, lowercase)
                        const query = this.searchQuery.replace('/', '').toLowerCase().trim();
                        const words = query.split(' ');
                        
                        return this.commands.filter(cmd => {
                            const text = (cmd.command + ' ' + cmd.label).toLowerCase();
                            // match ALL words
                            return words.every(word => text.includes(word));

                        });

                    }
                }
              
            })

            // Add components in lightbox header and message area
            $s('.dialog-widget-content').addClass('dialog-ai-widget-content-wrapper');
            $s('.dialog-lightbox-header').html('<template-ai-library-header></template-ai-library-header>');
            $s('.dialog-lightbox-message').html('<template-ai-library-content></template-ai-library-content>');
            // App mounting
            app.mount('#enteraddonsModal');

        },
        closeModal: function () {
            this.getModal().hide()
            
        },
        getModal: function() {

            var modalOptions = {
                    className: 'elementor-templates-modal',
                    id: 'enteraddonsModal',
                    closeButton: false,
                };
            this.modal = this.modal ||  elementor.dialogsManager.createWidget("lightbox", modalOptions )

            return this.modal;
        },
        
    },
    h = {

        showLoadingView: function () {
            $s('.dialog-lightbox-message').append( this.LoadingView() );
        },
        hideLoadingView: function () {
            $s( '.dialog-loading' ).remove();
        },
        LoadingView: function() {
            var l ='<div class="dialog-loading dialog-lightbox-loading" style="display: block;"><div id="elementor-template-library-loading">';
                     l += '<div class="elementor-loader-wrapper">';
                         l += '<div class="elementor-loader">';
                             l += '<div class="elementor-loader-boxes">';
                                 l += '<div class="elementor-loader-box"></div>';
                                 l += '<div class="elementor-loader-box"></div>';
                                 l += '<div class="elementor-loader-box"></div>';
                                 l += '<div class="elementor-loader-box"></div>';
                             l += '</div>';
                         l += '</div>';
                         l += '<div class="elementor-loading-title">Loading</div>';
                     l += '</div>';
                l += '</div></div>';

            $s( '.dialog-lightbox-message' ).append(l);
        }
    }

    $s(window).on("elementor:init", function() {
        window.elementor.on("preview:loaded", window._.bind( obj.onPreviewLoaded, obj ) );  
    })





})(jQuery);