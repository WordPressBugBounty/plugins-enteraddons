
<div class="enteraddons-dialog-lightbox-message-inner">

    <div class="enteraddons-ai-form-wrap">
        <form @submit.prevent="aiRequest">
            <p class="textarea-top-title"><?php esc_html_e( 'Create beautiful layouts with AI', 'enteraddons' ); ?></p>

            <div class="enteraddons-ai-messages" ref="chatMessages">
                
                <template v-for="(entry, index) in chatHistory" :key="index">
                    <div class="msg bot">{{ entry.prompt }}</div>
                    <div class="msg user">{{ entry.response }}</div>
                </template>

                <div class="ea-loader-progress-bar"></div>
            </div>

            <div class="enteraddons-prompt-field-block">
                <!-- Slash Command Dropdown -->
                <div v-if="showCommands">
                    <ul class="enteraddons-command-dropdown">
                        <li
                            v-for="(cmd, index) in filteredCommands"
                            :key="cmd.command"
                            :class="{ active: selectedIndex === index }"
                            @click="selectCommand(cmd)"
                        >
                            <strong>{{ cmd.command }}</strong>
                            <small>{{ cmd.label }}</small>
                        </li>
                    </ul>
                    <span @click="showCommands = false" class="enteraddons-command-dropdown-close">X</span>
                </div>

                <textarea v-model="form.prompt_text" @input="handleInput" @keydown="handleKeydown" placeholder="Press '/' for suggested prompts or describe the layout you want to create"></textarea>

            </div>

            <div class="enteraddons-suggestions enteraddons-model-type">
                <p><?php esc_html_e( 'Model', 'enteraddons' ); ?></p>
                <select :value="form.model_type" @change="setModelType($event.target.value)">
                    <?php echo Enteraddons\Classes\Helper::ea_ai_models_html(); ?>
                </select>
            </div>

            <div class="enteraddons-suggestions">
                <p><?php esc_html_e( 'Suggested Section:', 'enteraddons' ); ?></p>
                <ul>
                    <li @click="selectSuggestion('/hero')"><?php esc_html_e( 'Hero Section', 'enteraddons' ); ?></li>
                    <li @click="selectSuggestion('/features')"><?php esc_html_e( 'Feature Grid', 'enteraddons' ); ?></li>
                    <li @click="selectSuggestion('/pricing')"><?php esc_html_e( 'Pricing Table', 'enteraddons' ); ?></li>
                    <li @click="selectSuggestion('/testimonial')"><?php esc_html_e( 'Testimonial Table', 'enteraddons' ); ?></li>
                    <li @click="selectSuggestion('/services')"><?php esc_html_e( 'Service', 'enteraddons' ); ?></li>
                    <li class="suggested-for-more" @click="selectSuggestion('/')"><?php esc_html_e( '/ for more', 'enteraddons' ); ?></li>
                </ul>
            </div>

            <div class="enteraddons-ai-form-btn-wrap">
                <p v-if="responseMessage">{{ responseMessage }}</p>
                <button class="enteraddons-ai-generate-btn">✨ Generate Layout</button>
            </div>
            
        </form>
    </div>

</div>

