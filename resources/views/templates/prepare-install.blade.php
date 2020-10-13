fn_fetch_installer()

fn_fetch_installer(){
	fileurl="{{ $installer_url }}"
    fileurl_name="rustscp_installer.sh"

    local_filedir="/var/tmp"
	local_filename="rustscp_installer.sh"

    counter=1

    counter=$((counter+1))

    if [ ! -d "${local_filedir}" ]; then
        mkdir -p "${local_filedir}"
    fi
    # Trap will remove part downloaded files if canceled.
    trap fn_fetch_trap INT
    # Larger files show a progress bar.

    echo -en "fetching ${fileurl_name} ${local_filename}...\c"
    curlcmd=$(curl -s --fail -L -o "${local_filedir}/${local_filename}" "${fileurl}" 2>&1)

    local exitcode=$?

    # Download will fail if downloads a html file.
    if [ -f "${local_filedir}/${local_filename}" ]; then
        if [ -n "$(head "${local_filedir}/${local_filename}" | grep "DOCTYPE" )" ]; then
            rm "${local_filedir:?}/${local_filename:?}"
            local exitcode=2
        fi
    fi

    # On first try will error. On second try will fail.
    if [ "${exitcode}" != 0 ]; then
        if [ ${counter} -ge 2 ]; then
            echo -e "FAIL"
        else
            echo -e "ERROR"
        fi
    else
        echo -en "OK"
        sleep 0.3
        echo -en "\033[2K\\r"

        # Make file executable
        chmod +x "${local_filedir}/${local_filename}"

        # Remove trap.
        trap - INT

        break
    fi

	if [ -f "${local_filedir}/${local_filename}" ]; then
		# Execute file
        sh "${local_filedir}/${local_filename}"
	fi
}