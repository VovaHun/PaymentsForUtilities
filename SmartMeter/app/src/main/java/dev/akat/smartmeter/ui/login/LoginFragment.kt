package dev.akat.smartmeter.ui.login

import android.os.Bundle
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
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
import kotlinx.android.synthetic.main.fragment_login.*
import javax.inject.Inject

class LoginFragment : Fragment() {

    @Inject
    lateinit var viewModelFactory: ViewModelProvider.Factory

    private val appComponent: AppComponent by lazy(mode = LazyThreadSafetyMode.NONE) {
        (activity?.application as MeterApplication).appComponent
    }

    private val viewModel: LoginViewModel by viewModels { viewModelFactory }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        appComponent.inject(this)

        activity?.actionBar?.hide()
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? =
        inflater.inflate(R.layout.fragment_login, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        login_sign_in_btn.setOnClickListener {
            viewModel.authRequest()
        }

        login_register_btn.setOnClickListener {
            findNavController().navigate(R.id.action_loginFragment_to_registerFragment)
        }

        login_username_et.doAfterTextChanged { text -> viewModel.username = text?.toString() ?: "" }
        login_password_et.doAfterTextChanged { text -> viewModel.password = text?.toString() ?: "" }

        // form validation
        viewModel.isFormValid.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { valid ->
                login_sign_in_btn.isEnabled = valid
            }
        })

        // form errors
        viewModel.formErrors.observe(viewLifecycleOwner, Observer {
            login_username_input.error = ""
            login_password_input.error = ""

            for (error in it) {
                when (error) {
                    Validations.EMPTY_USERNAME -> {
                        login_username_input.error = getString(R.string.error_empty)
                    }

                    Validations.EMPTY_PASSWORD -> {
                        login_password_input.error = getString(R.string.error_empty)
                    }
                    else -> Log.d("LoginFragment", "Missing error")
                }
            }
        })

        // login error
        viewModel.errorMessage.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { message ->
                login_error_tv.text = message
            }
        })

        // success login
        viewModel.isLoggedIn.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { isLoggedIn ->
                if (isLoggedIn) findNavController().navigate(R.id.action_loginFragment_to_accountListFragment)
            }
        })
    }
}