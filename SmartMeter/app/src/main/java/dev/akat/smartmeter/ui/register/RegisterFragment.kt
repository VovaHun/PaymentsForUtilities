package dev.akat.smartmeter.ui.register

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.AdapterView
import android.widget.ArrayAdapter
import android.widget.Toast
import androidx.core.widget.doAfterTextChanged
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProvider
import androidx.navigation.fragment.findNavController
import dev.akat.smartmeter.MeterApplication
import dev.akat.smartmeter.R
import dev.akat.smartmeter.di.AppComponent
import dev.akat.smartmeter.utils.Validations
import kotlinx.android.synthetic.main.fragment_register.*
import javax.inject.Inject

class RegisterFragment : Fragment() {

    @Inject
    lateinit var viewModelFactory: ViewModelProvider.Factory

    private val appComponent: AppComponent by lazy(mode = LazyThreadSafetyMode.NONE) {
        (activity?.application as MeterApplication).appComponent
    }

    private val viewModel: RegisterViewModel by viewModels { viewModelFactory }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        appComponent.inject(this)
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? =
        inflater.inflate(R.layout.fragment_register, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        setListeners()
        observerErrors()

        viewModel.appealList.observe(viewLifecycleOwner, Observer {
            val appealAdapter =
                ArrayAdapter(requireContext(), android.R.layout.simple_spinner_item, it)
            appealAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item)
            reg_appeal_spinner.adapter = appealAdapter
            reg_appeal_spinner.setSelection(viewModel.appealType)
        })

        reg_appeal_spinner.onItemSelectedListener =
            object : AdapterView.OnItemSelectedListener {

                override fun onItemSelected(
                    parent: AdapterView<*>,
                    view: View?,
                    pos: Int,
                    id: Long
                ) {
                    viewModel.appealType = pos
                }

                override fun onNothingSelected(parent: AdapterView<*>) {
                }
            }

        viewModel.errorMessage.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { message ->
                if (message.isNotEmpty())
                    Toast.makeText(context, message, Toast.LENGTH_LONG).show()
            }
        })

        viewModel.isFetched.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { isFetched ->
                if (isFetched) {
                    Toast.makeText(
                        context,
                        resources.getString(R.string.success_sent),
                        Toast.LENGTH_LONG
                    ).show()
                    findNavController().navigate(R.id.action_registerFragment_to_accountListFragment)
                }
            }
        })
    }

    private fun observerErrors() {
        viewModel.formErrors.observe(viewLifecycleOwner, Observer {
            reg_username_input.error = null
            reg_password_input.error = null
            reg_password_confirm_input.error = null
            reg_name_input.error = null
            reg_email_name_input.error = null
            reg_phone_name_input.error = null
            reg_consent_cb.error = null

            for (error in it) {
                when (error) {
                    Validations.EMPTY_USERNAME -> reg_username_input.error =
                        getString(R.string.error_empty)
                    Validations.EMPTY_PASSWORD -> reg_password_input.error =
                        getString(R.string.error_empty)
                    Validations.CONFIRM_PASSWORD_ERROR -> reg_password_confirm_input.error =
                        getString(R.string.error_password_confirm)
                    Validations.EMPTY_NAME -> reg_name_input.error =
                        getString(R.string.error_empty)
                    Validations.EMAIL_ERROR -> reg_email_name_input.error =
                        getString(R.string.error_email)
                    Validations.PHONE_ERROR -> reg_phone_name_input.error =
                        getString(R.string.error_phone)
                    Validations.MISSING_CONSENT -> reg_consent_cb.error =
                        getString(R.string.error_empty)
                }
            }
        })
    }

    private fun setListeners() {
        reg_register_btn.setOnClickListener {
            viewModel.register()
        }

        reg_name_et.setOnFocusChangeListener { _, hasFocus ->
            if (!hasFocus) viewModel.name = reg_name_et.text.toString()
        }
        // text fields
        reg_username_et.doAfterTextChanged { viewModel.username = it?.toString() ?: "" }
        reg_password_et.doAfterTextChanged { viewModel.password = it?.toString() ?: "" }
        reg_password_confirm_et.doAfterTextChanged { viewModel.passwordConfirm = it?.toString() ?: "" }
        reg_email_name_et.doAfterTextChanged { viewModel.email = it?.toString() ?: "" }
        reg_phone_name_et.doAfterTextChanged { viewModel.phone = it?.toString() ?: "" }
        reg_comment_et.doAfterTextChanged { viewModel.comment = it?.toString() ?: "" }

        // checkboxes
        reg_email_notifications_cb.setOnCheckedChangeListener { _, isChecked ->
            viewModel.emailNotification = isChecked
        }

        reg_phone_notifications_cb.setOnCheckedChangeListener { _, isChecked ->
            viewModel.phoneNotification = isChecked
        }

        reg_consent_cb.setOnCheckedChangeListener { _, isChecked ->
            viewModel.consent = isChecked
        }

        // radio button
        reg_gender_rg.setOnCheckedChangeListener { _, _ ->
            run {
                viewModel.gender = if (reg_gender_male_rb.isChecked) 0 else 1
            }
        }

        // form validation
        viewModel.isFormValid.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { valid ->
                reg_register_btn.isEnabled = valid
            }
        })
    }
}