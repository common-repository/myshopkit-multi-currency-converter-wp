<form method="POST" action="<?php echo admin_url('admin.php?page=' . $this->getAuthSlug()); ?>">
    <?php wp_nonce_field('mskmc-auth-action', 'mskmc-auth-field'); ?>
    <table class="form-table">
        <thead>
        <tr>
            <th><?php echo esc_html__('Multi Currency Auth Settings', 'myshopkit-multi-currency-converter-wp'); ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>
                <label for="mskmc-username"><?php echo esc_html__('Username', 'myshopkit-multi-currency-converter-wp'); ?></label>
            </th>
            <td>
                <input id="mskmc-username" type="text" name="mskmc-auth[username]"
                       value="<?php echo esc_attr($this->aOptions['username']); ?>" required class="regular-text"/>
            </td>
        </tr>
        <tr>
            <th><label for="mskmc-app-password"><?php echo esc_html__('Application Password',
			            'myshopkit-multi-currency-converter-wp'); ?></label>
            </th>
            <td>
                <input id="mskmc-app-password" type="password" name="mskmc-auth[app_password]"
                       value="<?php echo esc_attr($this->aOptions['app_password']); ?>" required class="regular-text"/>
            </td>
        </tr>
        </tbody>
    </table>
    <button id="button-save" class="button button-primary" type="submit"><?php esc_html_e('Save Changes',
		    'myshopkit-multi-currency-converter-wp'); ?></button>
</form>
<?php if (!empty(get_option(MSKMC_PREFIX.'purchase_code'))): ?>
    <button id="btn-Revoke-Purchase-Code" class="button button-primary"><?php esc_html_e
        ('Revoke Purchase Code',
		    'myshopkit-multi-currency-converter-wp'); ?></button>
<?php endif; ?>
